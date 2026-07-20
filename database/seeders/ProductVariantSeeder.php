<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 1,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 2,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 3,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 4,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 1,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 1,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 2,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 3,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 2,
            'sizeID'        => 4,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 1,
            'colorID'       => 1,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 2,
            'colorID'       => 1,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 3,
            'colorID'       => 1,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 3,
            'sizeID'        => 4,
            'colorID'       => 1,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 4,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 4,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 4,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 4,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 5,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 5,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 5,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 5,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 6,
            'sizeID'        => 1,
            'colorID'       => 5,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 6,
            'sizeID'        => 2,
            'colorID'       => 5,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 6,
            'sizeID'        => 3,
            'colorID'       => 5,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 6,
            'sizeID'        => 4,
            'colorID'       => 5,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
         DB::table('product_variants')->insert([
            'productID'     => 7,
            'sizeID'        => 1,
            'colorID'       => 15,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 7,
            'sizeID'        => 2,
            'colorID'       => 15,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 7,
            'sizeID'        => 3,
            'colorID'       => 15,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 7,
            'sizeID'        => 4,
            'colorID'       => 15,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
         DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 1,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 2,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 3,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 4,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 8,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 9,
            'sizeID'        => 1,
            'colorID'       => 24,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 9,
            'sizeID'        => 2,
            'colorID'       => 24,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 9,
            'sizeID'        => 3,
            'colorID'       => 24,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 9,
            'sizeID'        => 4,
            'colorID'       => 24,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 10,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 10,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);DB::table('product_variants')->insert([
            'productID'     => 10,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 10,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 11,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 11,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 11,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 11,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 1,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 2,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 3,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 12,
            'sizeID'        => 4,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 13,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 13,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 13,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 13,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 1,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 2,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 3,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 4,
            'colorID'       => 6,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 1,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 2,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 3,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 14,
            'sizeID'        => 4,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 15,
            'sizeID'        => 1,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 15,
            'sizeID'        => 2,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 15,
            'sizeID'        => 3,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 15,
            'sizeID'        => 4,
            'colorID'       => 10,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
       
        DB::table('product_variants')->insert([
            'productID'     => 16,
            'sizeID'        => 1,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 16,
            'sizeID'        => 2,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 16,
            'sizeID'        => 3,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 16,
            'sizeID'        => 4,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 1,
            'colorID'       => 7,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 2,
            'colorID'       => 7,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 3,
            'colorID'       => 7,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 4,
            'colorID'       => 7,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
         DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 1,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 2,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 3,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 17,
            'sizeID'        => 4,
            'colorID'       => 21,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
         DB::table('product_variants')->insert([
            'productID'     => 18,
            'sizeID'        => 1,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 18,
            'sizeID'        => 2,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 18,
            'sizeID'        => 3,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 18,
            'sizeID'        => 4,
            'colorID'       => 23,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 1,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 2,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 3,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 4,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 1,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 2,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 3,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 4,
            'colorID'       => 19,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 1,
            'colorID'       => 16,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 2,
            'colorID'       => 16,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 3,
            'colorID'       => 16,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 19,
            'sizeID'        => 4,
            'colorID'       => 16,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 20,
            'sizeID'        => 1,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 20,
            'sizeID'        => 2,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 20,
            'sizeID'        => 3,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 20,
            'sizeID'        => 4,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
         DB::table('product_variants')->insert([
            'productID'     => 21,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 21,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 21,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 21,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 22,
            'sizeID'        => 1,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 22,
            'sizeID'        => 2,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 22,
            'sizeID'        => 3,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 22,
            'sizeID'        => 4,
            'colorID'       => 25,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 1,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 2,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 3,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 4,
            'colorID'       => 20,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 1,
            'colorID'       => 9,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 2,
            'colorID'       => 9,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 3,
            'colorID'       => 9,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
        DB::table('product_variants')->insert([
            'productID'     => 23,
            'sizeID'        => 4,
            'colorID'       => 9,
            'stockQuantity' => 10,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
