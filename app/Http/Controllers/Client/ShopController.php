<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categoryId = null)
    {
        $rootCategories = Category::with('children')->whereNull('parentID')->get();
        $sizes          = Size::orderBy('sizeName')->get();
        $colors         = Color::orderBy('colorName')->get();

        $namRoot = Category::where('categoryName', 'Nam')->whereNull('parentID')->first();
        $nuRoot  = Category::where('categoryName', 'Nữ')->whereNull('parentID')->first();

        $currentCategory = $categoryId ? Category::with('children')->find($categoryId) : null;

        $categoryIds = [];
        if ($currentCategory) {
            $categoryIds = $this->getAllCategoryIds($currentCategory);
        }

        $query = Product::with(['coverImage', 'variants.size', 'variants.color', 'category']);

        // Lọc theo danh mục
        if (!empty($categoryIds)) {
            $query->whereIn('categoryID', $categoryIds);
        }


        //Tìm kiếm thông minh
        if ($request->filled('search')) {
            $this->applySmartSearch($query, trim($request->search));
        }

        // Lọc theo giá
        if ($request->filled('min_price')) {
            $query->where('basePrice', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('basePrice', '<=', $request->max_price);
        }

        // Lọc theo size hoặc màu (qua variants) 
        if ($request->filled('sizes') || $request->filled('colors')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->filled('sizes')) {
                    $q->whereIn('sizeID', $request->sizes);
                }
                if ($request->filled('colors')) {
                    $q->whereIn('colorID', $request->colors);
                }
                $q->where('stockQuantity', '>', 0);
            });
        }

        // ── Xử lý các nút xem chi tiết từ trang Home ──
        if ($request->filled('filter')) {
            $now = \Carbon\Carbon::now();
            switch ($request->filter) {
                case 'latest':
                    $query->latest();
                    break;

                case 'sale':
                    $saleProductIds = \App\Models\ProductDiscount::where('isActive', true)
                        ->where('startDate', '<=', $now)->where('endDate', '>=', $now)
                        ->pluck('productID');
                    $query->whereIn('productID', $saleProductIds);
                    break;

                case 'bestseller':
                    $bestsellerIds = \Illuminate\Support\Facades\DB::table('order_details')
                        ->join('product_variants', 'order_details.variantID', '=', 'product_variants.variantID')
                        ->join('orders', 'order_details.orderID', '=', 'orders.orderID')
                        ->where('orders.status', 'Completed')
                        ->select('product_variants.productID', \Illuminate\Support\Facades\DB::raw('SUM(order_details.quantity) as total_sold'))
                        ->groupBy('product_variants.productID')
                        ->orderByDesc('total_sold')
                        ->pluck('productID');

                    if ($bestsellerIds->isNotEmpty()) {
                        $query->whereIn('productID', $bestsellerIds)
                            ->orderByRaw(\Illuminate\Support\Facades\DB::raw("FIELD(productID, " . implode(',', $bestsellerIds->toArray()) . ")"));
                    }
                    break;

                case 'favourite':
                    $query->inRandomOrder();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(16)->withQueryString();

        // ── BỔ SUNG: Thêm 'namRoot' và 'nuRoot' vào hàm compact() để truyền dữ liệu sang View ──
        return view('client.shop', compact(
            'rootCategories',
            'sizes',
            'colors',
            'currentCategory',
            'products',
            'categoryId',
            'namRoot',
            'nuRoot'
        ));
    }

    private function getAllCategoryIds(Category $category): array
    {
        $ids = [$category->categoryID];
        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }
        return $ids;
    }
    private function applySmartSearch($query, string $searchTerm): void
    {
        if ($searchTerm === '') {
            return;
        }
        $stopWords = ['size', 'mau', 'màu'];

        $searchWordsRaw = preg_split('/\s+/u', mb_strtolower($searchTerm, 'UTF-8'));
        $searchWords     = array_values(array_filter($searchWordsRaw, fn($w) => $w !== ''));

        // ===== 1. Xác định từ nào match MÀU =====
        $allColors = Color::all();
        $colorWords     = [];
        $matchedColorIds = collect();

        foreach ($searchWords as $word) {
            foreach ($allColors as $color) {
                if (mb_strpos(mb_strtolower($color->colorName, 'UTF-8'), $word) !== false) {
                    $colorWords[]      = $word;
                    $matchedColorIds->push($color->colorID);
                }
            }
        }
        $matchedColorIds = $matchedColorIds->unique();

        // ===== 2. Xác định từ nào match SIZE =====
        $allSizes = Size::all();
        $sizeWords     = [];
        $matchedSizeIds = collect();

        foreach ($searchWords as $word) {
            foreach ($allSizes as $size) {
                $sizeName = mb_strtolower($size->sizeName, 'UTF-8');
                $sizeCode = mb_strtolower($size->sizeCode, 'UTF-8');
                if (mb_strpos($sizeName, $word) !== false || $sizeCode === $word || mb_strpos($sizeCode, $word) !== false) {
                    $sizeWords[]      = $word;
                    $matchedSizeIds->push($size->sizeID);
                }
            }
        }
        $matchedSizeIds = $matchedSizeIds->unique();

        // ===== 3. Các từ còn lại (không phải màu/size/stopword) dùng để match CATEGORY =====
        $usedWords = array_merge($colorWords, $sizeWords, $stopWords);
        $categoryWords = array_values(array_filter($searchWords, fn($w) => !in_array($w, $usedWords, true)));

        $matchedCategoryIds = collect();
        if (!empty($categoryWords)) {
            $allCategories = Category::all()->keyBy('categoryID');

            foreach ($allCategories as $cat) {
                $rootName = $cat->categoryName;
                $node     = $cat;
                while ($node->parentID && $allCategories->has($node->parentID)) {
                    $node     = $allCategories->get($node->parentID);
                    $rootName = $node->categoryName;
                }

                $combined = mb_strtolower($cat->categoryName . ' ' . $rootName, 'UTF-8');

                $matchesAllWords = true;
                foreach ($categoryWords as $word) {
                    if (mb_strpos($combined, $word) === false) {
                        $matchesAllWords = false;
                        break;
                    }
                }

                if ($matchesAllWords) {
                    $matchedCategoryIds->push($cat->categoryID);
                }
            }
        }

        // ===== 4. Ghép điều kiện: các facet đã phát hiện phải AND với nhau =====
        $hasCategoryFacet = $matchedCategoryIds->isNotEmpty();
        $hasVariantFacet  = $matchedColorIds->isNotEmpty() || $matchedSizeIds->isNotEmpty();

        $query->where(function ($q) use (
            $searchTerm,
            $hasCategoryFacet,
            $hasVariantFacet,
            $matchedCategoryIds,
            $matchedColorIds,
            $matchedSizeIds
        ) {
            // Fallback: match trực tiếp theo tên sản phẩm
            $q->where('productName', 'like', '%' . $searchTerm . '%');

            if ($hasCategoryFacet || $hasVariantFacet) {
                $q->orWhere(function ($fq) use (
                    $hasCategoryFacet,
                    $hasVariantFacet,
                    $matchedCategoryIds,
                    $matchedColorIds,
                    $matchedSizeIds
                ) {
                    if ($hasCategoryFacet) {
                        $fq->whereIn('categoryID', $matchedCategoryIds);
                    }
                    if ($hasVariantFacet) {
                        $fq->whereHas('variants', function ($vq) use ($matchedColorIds, $matchedSizeIds) {
                            $vq->where('stockQuantity', '>', 0);
                            if ($matchedColorIds->isNotEmpty()) {
                                $vq->whereIn('colorID', $matchedColorIds);
                            }
                            if ($matchedSizeIds->isNotEmpty()) {
                                $vq->whereIn('sizeID', $matchedSizeIds);
                            }
                        });
                    }
                });
            }
        });
    }
    // private function applySmartSearch($query, string $searchTerm): void
    // {
    //     if ($searchTerm === '') {
    //         return;
    //     }

    //     $searchWordsRaw = preg_split('/\s+/u', mb_strtolower($searchTerm, 'UTF-8'));
    //     $searchWords     = array_filter($searchWordsRaw, fn($w) => $w !== '');

    //     $matchedColorIds = Color::where(function ($q) use ($searchTerm, $searchWords) {
    //         $q->where('colorName', 'like', '%' . $searchTerm . '%');
    //         foreach ($searchWords as $word) {
    //             $q->orWhere('colorName', 'like', '%' . $word . '%');
    //         }
    //     })->pluck('colorID');

    //     $matchedSizeIds = Size::where(function ($q) use ($searchTerm, $searchWords) {
    //         $q->where('sizeName', 'like', '%' . $searchTerm . '%')
    //           ->orWhere('sizeCode', 'like', '%' . $searchTerm . '%');
    //         foreach ($searchWords as $word) {
    //             $q->orWhere('sizeName', 'like', '%' . $word . '%')
    //               ->orWhere('sizeCode', 'like', '%' . $word . '%');
    //         }
    //     })->pluck('sizeID');

    //     $matchedCategoryIds = collect();
    //     $allCategories      = Category::all()->keyBy('categoryID');

    //     foreach ($allCategories as $cat) {
    //         $rootName = $cat->categoryName;
    //         $node     = $cat;
    //         while ($node->parentID && $allCategories->has($node->parentID)) {
    //             $node     = $allCategories->get($node->parentID);
    //             $rootName = $node->categoryName;
    //         }

    //         $combined = mb_strtolower($cat->categoryName . ' ' . $rootName, 'UTF-8');

    //         $matchesAllWords = true;
    //         foreach ($searchWords as $word) {
    //             if (mb_strpos($combined, $word) === false) {
    //                 $matchesAllWords = false;
    //                 break;
    //             }
    //         }

    //         if ($matchesAllWords) {
    //             $matchedCategoryIds->push($cat->categoryID);
    //         }
    //     }

    //     $query->where(function ($q) use ($searchTerm, $matchedColorIds, $matchedSizeIds, $matchedCategoryIds) {

    //         $q->where('productName', 'like', '%' . $searchTerm . '%');

    //         // Khớp theo danh mục (kể cả danh mục cha, VD "nam", "áo nam")
    //         if ($matchedCategoryIds->isNotEmpty()) {
    //             $q->orWhereIn('categoryID', $matchedCategoryIds);
    //         }

    //         // Khớp theo màu hoặc size của các biến thể còn hàng
    //         if ($matchedColorIds->isNotEmpty() || $matchedSizeIds->isNotEmpty()) {
    //             $q->orWhereHas('variants', function ($vq) use ($matchedColorIds, $matchedSizeIds) {
    //                 $vq->where('stockQuantity', '>', 0);
    //                 $vq->where(function ($iq) use ($matchedColorIds, $matchedSizeIds) {
    //                     if ($matchedColorIds->isNotEmpty()) {
    //                         $iq->orWhereIn('colorID', $matchedColorIds);
    //                     }
    //                     if ($matchedSizeIds->isNotEmpty()) {
    //                         $iq->orWhereIn('sizeID', $matchedSizeIds);
    //                     }
    //                 });
    //             });
    //         }
    //     });
    // }
}
