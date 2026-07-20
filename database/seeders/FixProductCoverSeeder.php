<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Chạy seeder này sau khi dùng bulk insert để gán ảnh đại diện (imageID)
 * cho tất cả sản phẩm chưa có ảnh đại diện.
 *
 * Quy tắc: ảnh đầu tiên (imageID nhỏ nhất) của mỗi sản phẩm = ảnh đại diện.
 *
 * Dùng: php artisan db:seed --class=FixProductCoverSeeder
 */
class FixProductCoverSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả sản phẩm chưa có imageID hoặc imageID không hợp lệ
        $products = DB::table('products')
            ->whereNull('imageID')
            ->orWhereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('product_images')
                  ->whereColumn('product_images.imageID', 'products.imageID');
            })
            ->get();

        $fixed = 0;
        foreach ($products as $product) {
            $firstImage = DB::table('product_images')
                ->where('productID', $product->productID)
                ->orderBy('imageID')
                ->first();

            if ($firstImage) {
                DB::table('products')
                    ->where('productID', $product->productID)
                    ->update(['imageID' => $firstImage->imageID]);
                $fixed++;
            }
        }

        $this->command->info("FixProductCoverSeeder: đã gán ảnh đại diện cho $fixed sản phẩm.");
    }
}
