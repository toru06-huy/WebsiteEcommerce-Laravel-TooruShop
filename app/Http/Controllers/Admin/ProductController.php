<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RestockRejectedMail;
use App\Mail\RestockRequestMail;
use App\Models\Product;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Color;
use App\Models\RestockRequest;
use App\Models\RestockRequestItem;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'manufacturer', 'variants', 'coverImage']);

        if ($request->filled('search')) {
            $this->applySmartSearch($query, $request->search);
        }

        if ($request->filled('category')) {
            $query->where('categoryID', $request->category);
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::doesntHave('children')
            ->with('parentRecursive')
            ->orderBy('categoryName')
            ->get();

        return view('admin.products.index', compact('products', 'categories'));
    }
    private function applySmartSearch($query, string $searchTerm): void
    {
        if ($searchTerm === '') {
            return;
        }

        $searchWordsRaw = preg_split('/\s+/u', mb_strtolower($searchTerm, 'UTF-8'));
        $searchWords     = array_filter($searchWordsRaw, fn($w) => $w !== '');

        $matchedColorIds = Color::where(function ($q) use ($searchTerm, $searchWords) {
            $q->where('colorName', 'like', '%' . $searchTerm . '%');
            foreach ($searchWords as $word) {
                $q->orWhere('colorName', 'like', '%' . $word . '%');
            }
        })->pluck('colorID');

        $matchedSizeIds = Size::where(function ($q) use ($searchTerm, $searchWords) {
            $q->where('sizeName', 'like', '%' . $searchTerm . '%')
                ->orWhere('sizeCode', 'like', '%' . $searchTerm . '%');
            foreach ($searchWords as $word) {
                $q->orWhere('sizeName', 'like', '%' . $word . '%')
                    ->orWhere('sizeCode', 'like', '%' . $word . '%');
            }
        })->pluck('sizeID');

        $matchedCategoryIds = collect();
        $allCategories      = Category::all()->keyBy('categoryID');

        foreach ($allCategories as $cat) {
            $rootName = $cat->categoryName;
            $node     = $cat;
            while ($node->parentID && $allCategories->has($node->parentID)) {
                $node     = $allCategories->get($node->parentID);
                $rootName = $node->categoryName;
            }

            $combined = mb_strtolower($cat->categoryName . ' ' . $rootName, 'UTF-8');

            $matchesAllWords = true;
            foreach ($searchWords as $word) {
                if (mb_strpos($combined, $word) === false) {
                    $matchesAllWords = false;
                    break;
                }
            }

            if ($matchesAllWords) {
                $matchedCategoryIds->push($cat->categoryID);
            }
        }

        $query->where(function ($q) use ($searchTerm, $matchedColorIds, $matchedSizeIds, $matchedCategoryIds) {

            $q->where('productName', 'like', '%' . $searchTerm . '%');

            // Khớp theo danh mục (kể cả danh mục cha, VD "nam", "áo nam")
            if ($matchedCategoryIds->isNotEmpty()) {
                $q->orWhereIn('categoryID', $matchedCategoryIds);
            }

            // Khớp theo màu hoặc size của các biến thể còn hàng
            if ($matchedColorIds->isNotEmpty() || $matchedSizeIds->isNotEmpty()) {
                $q->orWhereHas('variants', function ($vq) use ($matchedColorIds, $matchedSizeIds) {
                    $vq->where('stockQuantity', '>', 0);
                    $vq->where(function ($iq) use ($matchedColorIds, $matchedSizeIds) {
                        if ($matchedColorIds->isNotEmpty()) {
                            $iq->orWhereIn('colorID', $matchedColorIds);
                        }
                        if ($matchedSizeIds->isNotEmpty()) {
                            $iq->orWhereIn('sizeID', $matchedSizeIds);
                        }
                    });
                });
            }
        });
    }
    public function create()
    {
        $categories    = Category::orderBy('categoryName')->get();
        $manufacturers = Manufacturer::orderBy('manufacturerName')->get();
        $colors        = Color::orderBy('colorName')->get();
        $sizes         = Size::orderBy('sizeName')->get();

        return view('admin.products.form', compact('categories', 'manufacturers', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'productName'    => 'required|string|max:200',
            'categoryID'     => 'required|exists:categories,categoryID',
            'manufacturerID' => 'nullable|exists:manufacturers,manufacturerID',
            'basePrice'      => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'variants'       => 'nullable|array',
        ], [
            'productName.required' => 'Tên sản phẩm không được để trống.',
            'categoryID.required'  => 'Vui lòng chọn danh mục.',
            'basePrice.required'   => 'Vui lòng nhập giá sản phẩm.',
        ]);

        DB::transaction(function () use ($request, $data) {

            // 1. Tạo product trước — imageID = null
            $product = Product::create([
                'productName'    => $data['productName'],
                'categoryID'     => $data['categoryID'],
                'manufacturerID' => $data['manufacturerID'] ?? null,
                'basePrice'      => $data['basePrice'],
                'description'    => $data['description'] ?? null,
                'imageID'        => null,
            ]);

            // 2. Lưu ảnh — ảnh đầu tiên sẽ là ảnh đại diện (imageID)
            if ($request->hasFile('images')) {
                $firstImageID = null;

                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    $img = ProductImage::create([
                        'productID' => $product->productID,
                        'imageURL'  => $path,
                    ]);
                    if ($index === 0) {
                        $firstImageID = $img->imageID;
                    }
                }

                if ($firstImageID) {
                    $product->update(['imageID' => $firstImageID]);
                }
            }

            // 3. Lưu biến thể
            if ($request->filled('variants')) {
                foreach ($request->variants as $variant) {
                    if (!empty($variant['colorID']) || !empty($variant['sizeID'])) {
                        ProductVariant::create([
                            'productID'     => $product->productID,
                            'colorID'       => $variant['colorID']   ?? null,
                            'sizeID'        => $variant['sizeID']    ?? null,
                            'stockQuantity' => $variant['stock']     ?? 0,
                        ]);
                    }
                    if (!empty($existingIds)) {
                        // Chỉ xóa variants KHÔNG có trong đơn hàng
                        $product->variants()
                            ->whereNotIn('variantID', $existingIds)
                            ->whereDoesntHave('orderDetails')
                            ->delete();
                    }
                }
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã thêm sản phẩm "' . $data['productName'] . '".');
    }

    public function edit($id)
    {
        $product       = Product::with(['variants', 'images', 'coverImage'])->findOrFail($id);
        $categories    = Category::orderBy('categoryName')->get();
        $manufacturers = Manufacturer::orderBy('manufacturerName')->get();
        $colors        = Color::orderBy('colorName')->get();
        $sizes         = Size::orderBy('sizeName')->get();

        return view('admin.products.form', compact('product', 'categories', 'manufacturers', 'colors', 'sizes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'productName'    => 'required|string|max:200',
            'categoryID'     => 'required|exists:categories,categoryID',
            'manufacturerID' => 'nullable|exists:manufacturers,manufacturerID',
            'basePrice'      => 'required|numeric|min:0',
            'description'    => 'nullable|string',
            'images.*'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'variants'       => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $product, $data) {

            // 1. Cập nhật thông tin cơ bản
            $updateData = [
                'productName'    => $data['productName'],
                'categoryID'     => $data['categoryID'],
                'manufacturerID' => $data['manufacturerID'] ?? null,
                'basePrice'      => $data['basePrice'],
                'description'    => $data['description'] ?? null,
            ];

            $product->update($updateData);
            $product->refresh();

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    $img = ProductImage::create([
                        'productID' => $product->productID,
                        'imageURL'  => $path,
                    ]);

                    // Nếu product chưa có imageID → gán ảnh đầu tiên upload làm đại diện
                    if ($index === 0 && is_null($product->imageID)) {
                        $product->update(['imageID' => $img->imageID]);
                    }
                }
            }

            // 3. Cập nhật biến thể
            if ($request->has('variants')) {
                $existingIds = [];

                // Kiểm tra trùng lặp trong request
                $seenCombinations = [];
                foreach ($request->variants ?? [] as $idx => $variantData) {
                    $colorID = $variantData['colorID'] ?? null;
                    $sizeID  = $variantData['sizeID']  ?? null;

                    if (empty($colorID) && empty($sizeID)) continue;

                    $key = $colorID . '_' . $sizeID;
                    if (isset($seenCombinations[$key])) {
                        DB::rollBack();
                        return redirect()->back()
                            ->with('error', "Biến thể trùng lặp: color #{$colorID} + size #{$sizeID}");
                    }
                    $seenCombinations[$key] = true;
                }

                foreach ($request->variants ?? [] as $variantData) {
                    $colorID = $variantData['colorID'] ?? null;
                    $sizeID  = $variantData['sizeID']  ?? null;

                    if (empty($colorID) && empty($sizeID)) continue;

                    if (!empty($variantData['variantID'])) {
                        $duplicate = ProductVariant::where('productID', $product->productID)
                            ->where('colorID', $colorID)
                            ->where('sizeID',  $sizeID)
                            ->where('variantID', '!=', $variantData['variantID'])
                            ->exists();

                        if ($duplicate) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', "Biến thể color #{$colorID} + size #{$sizeID} đã tồn tại.");
                        }

                        $variant = ProductVariant::find($variantData['variantID']);
                        if ($variant && $variant->productID === $product->productID) {
                            $variant->update([
                                'colorID'       => $colorID,
                                'sizeID'        => $sizeID,
                                'stockQuantity' => $variantData['stock'] ?? 0,
                            ]);
                            $existingIds[] = $variant->variantID;
                        }
                    } else {
                        $duplicate = ProductVariant::where('productID', $product->productID)
                            ->where('colorID', $colorID)
                            ->where('sizeID',  $sizeID)
                            ->exists();

                        if ($duplicate) {
                            DB::rollBack();
                            return redirect()->back()
                                ->with('error', "Biến thể color #{$colorID} + size #{$sizeID} đã tồn tại.");
                        }

                        $new = ProductVariant::create([
                            'productID'     => $product->productID,
                            'colorID'       => $colorID,
                            'sizeID'        => $sizeID,
                            'stockQuantity' => $variantData['stock'] ?? 0,
                        ]);
                        $existingIds[] = $new->variantID;
                    }
                }

                $product->variants()->whereNotIn('variantID', $existingIds)
                    ->whereDoesntHave('orderDetails')
                    ->delete();
            } else {
                $product->variants()->whereDoesntHave('orderDetails')->delete();
            }
        });

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy($id)
    {
        $product = Product::with('images')->findOrFail($id);

        $hasOrders = $product->variants()
            ->whereHas('orderDetails')
            ->exists();

        if ($hasOrders) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Không thể xóa sản phẩm "' . $product->productName . '" vì đã có trong đơn hàng.');
        }

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->imageURL);
        }

        $name = $product->productName;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm "' . $name . '".');
    }

    public function deleteImage($imageId)
    {
        $image   = ProductImage::findOrFail($imageId);
        $product = Product::find($image->productID);

        // Nếu đây là ảnh đại diện → tìm ảnh khác thay thế
        if ($product && $product->imageID === $image->imageID) {
            $nextImage = ProductImage::where('productID', $product->productID)
                ->where('imageID', '!=', $imageId)
                ->first();

            $product->update(['imageID' => $nextImage?->imageID]);
        }

        Storage::disk('public')->delete($image->imageURL);
        $image->delete();

        return back()->with('success', 'Đã xóa ảnh.');
    }
    // public function restock(Request $request, $id)
    // {
    //     $product = Product::with('variants')->findOrFail($id);

    //     $request->validate([
    //         'restock'   => 'required|array',
    //         'restock.*' => 'nullable|integer|min:0',
    //     ]);

    //     $updated = 0;
    //     foreach ($request->input('restock', []) as $variantID => $qty) {
    //         $qty = (int) $qty;
    //         if ($qty > 0) {
    //             ProductVariant::where('variantID', $variantID)
    //                 ->where('productID', $product->productID)
    //                 ->increment('stockQuantity', $qty);
    //             $updated++;
    //         }
    //     }

    //     if ($updated === 0) {
    //         return redirect()->route('admin.products.index')
    //             ->with('error', 'Vui lòng nhập số lượng cần bổ sung cho ít nhất 1 biến thể.');
    //     }

    //     return redirect()->route('admin.products.index')
    //         ->with('success', 'Đã nhập hàng cho "' . $product->productName . '" (' . $updated . ' biến thể).');
    // }
    public function restock(Request $request, $id)
    {
        $product = Product::with('manufacturer')->findOrFail($id);

        if (!$product->manufacturer || empty($product->manufacturer->email)) {
            return back()->with(
                'error',
                'Nhà cung cấp của sản phẩm này chưa có email. Vui lòng cập nhật email nhà cung cấp trước khi tạo yêu cầu nhập hàng.'
            );
        }

        $rows = collect($request->input('restock', []))
            ->map(fn($qty, $variantId) => [
                'variantID' => (int) $variantId,
                'quantity'  => (int) $qty,
            ])
            ->filter(fn($row) => $row['quantity'] > 0)
            ->values();

        if ($rows->isEmpty()) {
            return back()->with('error', 'Vui lòng nhập số lượng cần nhập cho ít nhất một biến thể.');
        }

        $restockRequest = DB::transaction(function () use ($product, $rows) {
            $restockRequest = RestockRequest::create([
                'productID'      => $product->productID,
                'manufacturerID' => $product->manufacturerID,
                'token'          => Str::random(64),
                'status'         => RestockRequest::STATUS_PENDING,
                'requestedBy'    => Auth::user()->employeeID ?? NULL,
            ]);

            foreach ($rows as $row) {
                RestockRequestItem::create([
                    'restockRequestID' => $restockRequest->restockRequestID,
                    'variantID'        => $row['variantID'],
                    'quantity'         => $row['quantity'],
                ]);
            }

            return $restockRequest;
        });

        Mail::to($product->manufacturer->email)->send(new RestockRequestMail($restockRequest));

        return back()->with('success', 'Đã gửi yêu cầu nhập hàng đến nhà cung cấp (' . $product->manufacturer->email . '). Kho sẽ chỉ được cập nhật sau khi nhân viên kiểm hàng và xác nhận nhập kho chính thức.');
    }
    public function restockRequests()
    {
        $requests = RestockRequest::with([
            'product',
            'manufacturer',
            'items.variant.size',
            'items.variant.color',
        ])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function ($r) {
                return [
                    'id'              => $r->restockRequestID,
                    'productName'     => $r->product->productName ?? '—',
                    'manufacturer'    => $r->manufacturer->manufacturerName ?? '—',
                    'status'          => $r->status, // 'pending' | 'supplier_confirmed' | 'completed' | 'cancelled'
                    'createdAt'       => $r->created_at->format('d/m/Y H:i'),
                    'confirmedAt'     => $r->confirmedAt ? $r->confirmedAt->format('d/m/Y H:i') : null, // NCC xác nhận lúc nào
                    'receivedAt'      => $r->receivedAt ? $r->receivedAt->format('d/m/Y H:i') : null,   // nhập kho chính thức lúc nào
                    'cancelledAt'     => $r->cancelledAt ? $r->cancelledAt->format('d/m/Y H:i') : null,
                    'cancelledByType' => $r->cancelledByType, // 'supplier' | 'staff' | null
                    'cancelReason'    => $r->cancelReason,
                    'items'           => $r->items->map(fn($i) => [
                        'label'    => ($i->variant->size->sizeName ?? '?') . ' / ' . ($i->variant->color->colorName ?? '?'),
                        'quantity' => $i->quantity,
                    ]),
                ];
            });

        return response()->json($requests);
    }

    public function receiveStock($id)
    {
        $restockRequest = RestockRequest::with('items')->findOrFail($id);

        if (!$restockRequest->isSupplierConfirmed()) {
            return response()->json([
                'success' => false,
                'message' => $restockRequest->isCompleted()
                    ? 'Yêu cầu này đã được nhập kho trước đó.'
                    : 'Chỉ có thể nhập kho sau khi nhà cung cấp đã xác nhận.',
            ], 422);
        }

        DB::transaction(function () use ($restockRequest) {
            $locked = RestockRequest::where('restockRequestID', $restockRequest->restockRequestID)
                ->where('status', RestockRequest::STATUS_SUPPLIER_CONFIRMED)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                return;
            }

            $items = $locked->items()->get();

            foreach ($items as $item) {
                ProductVariant::where('variantID', $item->variantID)
                    ->increment('stockQuantity', $item->quantity);
            }

            $locked->status     = RestockRequest::STATUS_COMPLETED;
            $locked->receivedBy = Auth::user()->employeeID ?? NULL;
            $locked->receivedAt = now();
            $locked->save();
        });

        return response()->json(['success' => true, 'message' => 'Đã nhập kho chính thức.']);
    }

    // ---- Method 4: nhân viên kiểm hàng KHÔNG đạt -> từ chối nhận hàng + gửi email lý do cho NCC ----
    public function rejectReceipt(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:2000',
        ], [
            'reason.required' => 'Vui lòng nhập lý do từ chối nhận hàng.',
        ]);

        $restockRequest = RestockRequest::with(['items', 'manufacturer', 'product'])->findOrFail($id);

        if (!$restockRequest->isSupplierConfirmed()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể từ chối nhận hàng khi nhà cung cấp đã xác nhận và chưa nhập kho.',
            ], 422);
        }

        DB::transaction(function () use ($restockRequest, $request) {
            $locked = RestockRequest::where('restockRequestID', $restockRequest->restockRequestID)
                ->where('status', RestockRequest::STATUS_SUPPLIER_CONFIRMED)
                ->lockForUpdate()
                ->first();

            if (!$locked) {
                return;
            }

            $locked->status            = RestockRequest::STATUS_CANCELLED;
            $locked->cancelReason      = $request->input('reason');
            $locked->cancelledByType   = RestockRequest::CANCELLED_BY_STAFF;
            $locked->cancelledByUserID = Auth::user()->employeeID ?? NULL;
            $locked->cancelledAt       = now();
            $locked->save();
        });

        $restockRequest->refresh();

        if ($restockRequest->manufacturer && $restockRequest->manufacturer->email) {
            Mail::to($restockRequest->manufacturer->email)->send(new RestockRejectedMail($restockRequest));
        }

        return response()->json(['success' => true, 'message' => 'Đã từ chối nhận hàng và gửi email thông báo cho nhà cung cấp.']);
    }
}
