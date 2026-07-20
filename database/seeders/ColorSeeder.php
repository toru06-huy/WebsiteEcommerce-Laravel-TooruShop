<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['colorCode' => '#FF0000', 'colorName' => 'Đỏ'],
            ['colorCode' => '#00FF00', 'colorName' => 'Xanh lá'],
            ['colorCode' => '#008000', 'colorName' => 'Xanh rêu'],
            ['colorCode' => '#008080', 'colorName' => 'Xanh ngọc'],
            ['colorCode' => '#0000FF', 'colorName' => 'Xanh dương'],
            ['colorCode' => '#000080', 'colorName' => 'Xanh Navy'],
            ['colorCode' => '#FFFF00', 'colorName' => 'Vàng'],
            ['colorCode' => '#ff16ff', 'colorName' => 'Hồng'],
            ['colorCode' => '#FFC0CB', 'colorName' => 'Hồng nhạt'],
            ['colorCode' => '#00FFFF', 'colorName' => 'Xanh lơ'],
            ['colorCode' => '#FFA500', 'colorName' => 'Cam'],
            ['colorCode' => '#800080', 'colorName' => 'Tím'],
            ['colorCode' => '#A52A2A', 'colorName' => 'Nâu'],
            ['colorCode' => '#D2B48C', 'colorName' => 'Nâu nhạt'],
            ['colorCode' => '#A0522D', 'colorName' => 'Nâu đỏ'],
            ['colorCode' => '#CD853F', 'colorName' => 'Nâu vàng'],
            ['colorCode' => '#8B4513', 'colorName' => 'Nâu sẫm'],
            ['colorCode' => '#4B0082', 'colorName' => 'Chàm'],
            ['colorCode' => '#000000', 'colorName' => 'Đen'],
            ['colorCode' => '#FFFFFF', 'colorName' => 'Trắng'],
            ['colorCode' => '#800000', 'colorName' => 'Đỏ sẫm'],
            ['colorCode' => '#808000', 'colorName' => 'Vàng đậm'],
            ['colorCode' => '#808080', 'colorName' => 'Xám'],
            ['colorCode' => '#C0C0C0', 'colorName' => 'Bạc'],
            ['colorCode' => '#fbfbd6', 'colorName' => 'Be'],
        ];

        foreach ($colors as $color) {
            DB::table('colors')->insert([
                'colorCode' => $color['colorCode'],
                'colorName' => $color['colorName'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
