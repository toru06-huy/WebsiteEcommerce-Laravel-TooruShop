<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('manufacturers')->insert([
            'manufacturerCode' => 'NCC001',
            'manufacturerName' => 'Công ty TNHH Thời Trang Việt',
            'country'          => 'Việt Nam',
            'website'          => 'https://thoitrangviet.vn',
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ]);
        DB::table('manufacturers')->insert([
    [
        'manufacturerCode' => 'NCC002',
        'manufacturerName' => 'Công ty CP Thời Trang Sài Gòn',
        'country'          => 'Việt Nam',
        'website'          => 'https://thoitrangsaigon.vn',
        'created_at'       => Carbon::now(),
        'updated_at'       => Carbon::now(),
    ],
    [
        'manufacturerCode' => 'NCC003',
        'manufacturerName' => 'Global Fashion Co., Ltd',
        'country'          => 'Hàn Quốc',
        'website'          => 'https://globalfashion.kr',
        'created_at'       => Carbon::now(),
        'updated_at'       => Carbon::now(),
    ],
    [
        'manufacturerCode' => 'NCC004',
        'manufacturerName' => 'Tokyo Apparel Group',
        'country'          => 'Nhật Bản',
        'website'          => 'https://tokyoapparel.jp',
        'created_at'       => Carbon::now(),
        'updated_at'       => Carbon::now(),
    ],
    [
        'manufacturerCode' => 'NCC005',
        'manufacturerName' => 'Guangzhou Textile Ltd',
        'country'          => 'Trung Quốc',
        'website'          => 'https://gztextile.cn',
        'created_at'       => Carbon::now(),
        'updated_at'       => Carbon::now(),
    ],
    [
        'manufacturerCode' => 'NCC006',
        'manufacturerName' => 'Bangkok Fashion Hub',
        'country'          => 'Thái Lan',
        'website'          => 'https://bangkokfashion.co.th',
        'created_at'       => Carbon::now(),
        'updated_at'       => Carbon::now(),
    ],
]);
    }
}