<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

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

        // Sản phẩm liên quan (cùng danh mục)
        $related = Product::with(['coverImage', 'variants'])
            ->where('categoryID', $product->categoryID)
            ->where('productID', '!=', $product->productID)
            ->take(4)
            ->get();

        return view('client.product', compact('product', 'rootCategories', 'variantMap', 'related'));
    }
}
