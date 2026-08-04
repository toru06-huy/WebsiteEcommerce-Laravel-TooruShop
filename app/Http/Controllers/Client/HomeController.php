<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductDiscount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private function getAllChildIds(int $rootId): array
    {
        $all  = [];
        $queue = [$rootId];

        while (!empty($queue)) {
            $ids = $queue;
            $queue = [];
            $children = Category::whereIn('parentID', $ids)->pluck('categoryID')->toArray();
            $all   = array_merge($all, $children);
            $queue = $children;
        }

        return $all;
    }

    public function index()
    {
        $rootCategories = Category::with('children')->whereNull('parentID')->get();
        $now = Carbon::now();

        $bannerDiscount = Discount::where('isActive', true)->where('isPersonal', false)
            ->where('endDate', '>=', $now)
            ->orderByDesc('discountValue')->orderBy('minOrderValue')->take(2)->get();

        $bestSaleProduct = ProductDiscount::with('product.coverImage')
            ->where('isActive', true)->where('startDate', '<=', $now)->where('endDate', '>=', $now)
            ->orderByDesc('discountValue')->first()?->product;

        $hotProduct = Product::with(['coverImage', 'category'])->where('categoryID', 9 )
            ->withSum('variants as total_stock', 'stockQuantity')
            ->orderBy('total_stock', 'desc')
            ->first();

        // ── Featured (mới nhất) ────────────────────────────────────────────
        $featuredProducts = Product::with(['coverImage', 'variants', 'productDiscounts'])
            ->latest()->take(8)->get();

        // ── Đang giảm giá ─────────────────────────────────────────────────
        $saleProductIds = ProductDiscount::where('isActive', true)
            ->where('startDate', '<=', $now)->where('endDate', '>=', $now)
            ->orderByDesc('discountValue')->pluck('productID');

        $saleProducts = Product::with(['coverImage', 'variants', 'productDiscounts'])
            ->whereIn('productID', $saleProductIds)->take(8)->get()
            ->sortByDesc(fn($p) => $p->activeDiscount()?->discountValue ?? 0);

        // ── Bán chạy (từ orders hoàn thành) ───────────────────────────────
        $bestsellerIds = DB::table('order_details')
            ->join('product_variants', 'order_details.variantID', '=', 'product_variants.variantID')
            ->join('orders', 'order_details.orderID', '=', 'orders.orderID')
            ->where('orders.status', 'Completed')
            ->select('product_variants.productID', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('product_variants.productID')
            ->orderByDesc('total_sold')
            ->limit(8)->pluck('productID');

        $bestsellerProducts = Product::with(['coverImage', 'variants', 'productDiscounts'])
            ->whereIn('productID', $bestsellerIds)->get()
            ->sortBy(fn($p) => array_search($p->productID, $bestsellerIds->toArray()));

        // ── Yêu thích (ngẫu nhiên) ────────────────────────────────────────
        $favouriteProducts = Product::with(['coverImage', 'variants', 'productDiscounts'])
            ->inRandomOrder()->take(8)->get();

        // ── Sản phẩm Nam (đệ quy toàn bộ cây danh mục) ───────────────────
        $namRoot = Category::where('categoryName', 'Nam')->whereNull('parentID')->first();
        $maleCategoryIds = $namRoot
            ? array_merge([$namRoot->categoryID], $this->getAllChildIds($namRoot->categoryID))
            : [];

        $maleProducts = $maleCategoryIds
            ? Product::with(['coverImage', 'variants', 'productDiscounts'])
                ->whereIn('categoryID', $maleCategoryIds)->latest()->take(8)->get()
            : collect();

        // ── Sản phẩm Nữ (đệ quy toàn bộ cây danh mục) ───────────────────
        $nuRoot = Category::where('categoryName', 'Nữ')->whereNull('parentID')->first();
        $femaleCategoryIds = $nuRoot
            ? array_merge([$nuRoot->categoryID], $this->getAllChildIds($nuRoot->categoryID))
            : [];

        $femaleProducts = $femaleCategoryIds
            ? Product::with(['coverImage', 'variants', 'productDiscounts'])
                ->whereIn('categoryID', $femaleCategoryIds)->latest()->take(8)->get()
            : collect();

        return view('client.home', compact(
            'rootCategories', 'featuredProducts', 'bannerDiscount', 'bestSaleProduct',
            'hotProduct', 'saleProducts', 'bestsellerProducts', 'favouriteProducts',
            'maleProducts', 'femaleProducts', 'now', 'namRoot', 'nuRoot'
        ));
    }
}