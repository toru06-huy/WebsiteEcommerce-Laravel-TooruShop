<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('discounts')->insert([
            'discountCode'  => 'VELOUR10',
            'discountName'  => 'Giảm 10% cho đơn hàng đầu tiên',
            'discountType'    => 'percentage',
            'discountValue' => 10.00,
            'discountLimit' => 100,
            'startDate'     => Carbon::now(),
            'endDate'       => Carbon::now()->addMonths(3),
            'minOrderValue' => 200000.00,
            'isActive'      => true,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}