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

        // ── BỔ SUNG: Định nghĩa $namRoot và $nuRoot để tránh lỗi Undefined variable ──
        $namRoot = Category::where('categoryName', 'Nam')->whereNull('parentID')->first();
        $nuRoot  = Category::where('categoryName', 'Nữ')->whereNull('parentID')->first();

        $currentCategory = $categoryId ? Category::with('children')->find($categoryId) : null;

        // Lấy tất cả categoryID con của category hiện tại (bao gồm chính nó)
        $categoryIds = [];
        if ($currentCategory) {
            $categoryIds = $this->getAllCategoryIds($currentCategory);
        }

        $query = Product::with(['coverImage', 'variants.size', 'variants.color', 'category']);

        // Lọc theo danh mục
        if (!empty($categoryIds)) {
            $query->whereIn('categoryID', $categoryIds);
        }

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('productName', 'like', '%' . $request->search . '%');
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
}
