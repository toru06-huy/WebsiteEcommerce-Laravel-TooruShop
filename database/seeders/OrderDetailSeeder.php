<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        $orderID   = DB::table('orders')->value('orderID');
        $variantID = DB::table('product_variants')->value('variantID');

        DB::table('order_details')->insert([
            'orderID'    => $orderID,
            'variantID'  => $variantID,
            'quantity'   => 1,
            'unitPrice'  => 350000.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}