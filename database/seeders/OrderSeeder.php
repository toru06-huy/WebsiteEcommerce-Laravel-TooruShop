<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy customer vừa seed
        $userID     = DB::table('users')->where('role', 'Customer')->value('userID');
        $employeeID = DB::table('employees')->value('employeeID');
        $discountID = DB::table('discounts')->value('discountID');

        DB::table('orders')->insert([
            'userID'          => $userID,
            'orderDate'       => Carbon::now(),
            'totalAmount'     => 350000.00,
            'discountID'      => $discountID,
            'discountAmount'  => 35000.00,
            'finalAmount'     => 315000.00,
            'status'          => 'Pending',
            'shippingAddress' => '123 Đường Lê Lợi, Quận 1, TP.HCM',
            'payment'         => 'Trả sau khi nhận'  ,
            'processedBy'     => $employeeID,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        ]);
    }
}