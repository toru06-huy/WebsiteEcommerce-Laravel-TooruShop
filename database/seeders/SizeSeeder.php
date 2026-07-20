<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            ['sizeCode' => 'S', 'sizeName' => 'Nhỏ'],
            ['sizeCode' => 'M', 'sizeName' => 'Vừa'],
            ['sizeCode' => 'L', 'sizeName' => 'Lớn'],
            ['sizeCode' => 'XL', 'sizeName' => 'Cực lớn'],
        ];

        foreach ($sizes as $size) {
            DB::table('sizes')->insert([
                'sizeCode' => $size['sizeCode'],
                'sizeName' => $size['sizeName'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}