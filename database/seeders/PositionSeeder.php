<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('positions')->insert([
            'positionCode' => 'MGR',
            'positionName' => 'Quản lý cửa hàng',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

         DB::table('positions')->insert([
            'positionCode' => 'NVF',
            'positionName' => 'Nhân viên thường trực',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

         DB::table('positions')->insert([
            'positionCode' => 'NVP',
            'positionName' => 'Nhân viên part time',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
    }
}