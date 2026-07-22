<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'manufacturer', 'variants', 'coverImage']);

        if ($request->filled('search')) {
            $query->where('productName', 'like', '%' . $request->search . '%');
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
    public function restock(Request $request, $id)
    {
        $product = Product::with('variants')->findOrFail($id);

        $request->validate([
            'restock'   => 'required|array',
            'restock.*' => 'nullable|integer|min:0',
        ]);

        $updated = 0;
        foreach ($request->input('restock', []) as $variantID => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                ProductVariant::where('variantID', $variantID)
                    ->where('productID', $product->productID)
                    ->increment('stockQuantity', $qty);
                $updated++;
            }
        }

        if ($updated === 0) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Vui lòng nhập số lượng cần bổ sung cho ít nhất 1 biến thể.');
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã nhập hàng cho "' . $product->productName . '" (' . $updated . ' biến thể).');
    }
}
