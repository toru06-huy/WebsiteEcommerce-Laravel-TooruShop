<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PageView;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with([
            'images',
            'coverImage',
            'category.parent',
            'manufacturer',
            'variants.size',
            'variants.color',
        ])->findOrFail($id);

        $rootCategories = Category::with('children')->whereNull('parentID')->get();

        // Tổ chức variants theo màu -> size
        $variantMap = [];
        foreach ($product->variants as $variant) {
            $colorId  = $variant->colorID;
            $colorName = $variant->color->colorName ?? 'N/A';
            $colorHex  = $variant->color->colorHex  ?? '#cccccc';
            $sizeId   = $variant->sizeID;
            $sizeName = $variant->size->sizeName ?? 'N/A';

            if (!isset($variantMap[$colorId])) {
                $variantMap[$colorId] = [
                    'colorID'   => $colorId,
                    'colorName' => $colorName,
                    'colorHex'  => $colorHex,
                    'sizes'     => [],
                ];
            }
            $variantMap[$colorId]['sizes'][$sizeId] = [
                'sizeID'        => $sizeId,
                'sizeName'      => $sizeName,
                'stockQuantity' => $variant->stockQuantity,
                'variantID'     => $variant->variantID,
            ];
        }
        $viewProducts = collect();

        if (Auth::check()) {
            $paths = PageView::where('userID', Auth::id())
                ->where('path', 'like', '%chi-tiet/%')
                ->orderByDesc('created_at')
                ->limit(30)
                ->pluck('path');

            $recentIds = $paths
                 ->map(function ($path) {preg_match('/chi-tiet\/(\d+)/', $path, $matches); return isset($matches[1]) ? (int) $matches[1] : 0;})
                ->filter(fn($pid) => $pid > 0 && $pid != $id) 
                ->unique()
                ->take(4)
                ->values();

            if ($recentIds->isNotEmpty()) {
                $ids = $recentIds->implode(',');

                $viewProducts = Product::with(['coverImage', 'variants'])
                    ->whereIn('productID', $recentIds)
                    ->orderByRaw("FIELD(productID, $ids)") 
                    ->get();
            }
        }
        // Sản phẩm liên quan (cùng danh mục)
        $related = Product::with(['coverImage', 'variants'])
            ->where('categoryID', $product->categoryID)
            ->where('productID', '!=', $product->productID)
            ->take(4)
            ->get();

        return view('client.product', compact('product', 'rootCategories', 'variantMap', 'related','viewProducts'));
    }
}
