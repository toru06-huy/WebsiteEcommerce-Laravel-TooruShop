<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Tạo các danh mục chính
        $nam=DB::table('categories')->insertGetId([
            'categoryName' => 'Nam',
            'description'  => 'Các loại thời trang nam',
            'parentID'     => null,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        $nu=DB::table('categories')->insertGetId([
            'categoryName' => 'Nữ',
            'description'  => 'Các loại thời trang nữ',
            'parentID'     => null,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        DB::table('categories')->insert([
            'categoryName' => 'Phụ kiện',
            'description'  => 'Các loại phụ kiện thời trang nam nữ',
            'parentID'     => null,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

// Tạo các danh mục con cho Nam và Nữ

        $qNam=DB::table('categories')->insertGetId([
            'categoryName' => 'Quần nam',
            'description'  => 'Các loại quần thời trang nam',
            'parentID'     => $nam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        $aNam=DB::table('categories')->insertGetId([
            'categoryName' => 'Áo nam',
            'description'  => 'Các loại áo thời trang nam',
            'parentID'     => $nam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        $qNu=DB::table('categories')->insertGetId([
            'categoryName' => 'Quần nữ',
            'description'  => 'Các loại quần thời trang nữ',
            'parentID'     => $nu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        $vNu=  DB::table('categories')->insertGetId([
            'categoryName' => 'Váy nữ',
            'description'  => 'Các loại váy thời trang nữ',
            'parentID'     => $nu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
          $aNu=DB::table('categories')->insertGetId([
            'categoryName' => 'Áo nữ',
            'description'  => 'Các loại áo thời trang nữ',
            'parentID'     => $nu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        // Tạo các danh mục con cho Áo nam và Áo nữ
        DB::table('categories')->insertGetId([
            'categoryName' => 'Áo Sơ mi',
            'description'  => 'Các loại áo sơ mi thời trang nam',
            'parentID'     => $aNam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
         DB::table('categories')->insertGetId([
            'categoryName' => 'Áo Thun',
            'description'  => 'Các loại áo thun thời trang nam',
            'parentID'     => $aNam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
         DB::table('categories')->insertGetId([
            'categoryName' => 'Áo khoác',
            'description'  => 'Các loại áo khoác thời trang nam',
            'parentID'     => $aNam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        
         DB::table('categories')->insertGetId([
            'categoryName' => 'Áo Thun',
            'description'  => 'Các loại áo thun thời trang nữ',
            'parentID'     => $aNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
          DB::table('categories')->insertGetId([
            'categoryName' => 'Áo Sơ mi',
            'description'  => 'Các loại áo sơ mi thời trang nữ',
            'parentID'     => $aNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
          DB::table('categories')->insertGetId([
            'categoryName' => 'Áo Len',
            'description'  => 'Các loại áo len thời trang nữ',
            'parentID'     => $aNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);

        // Tạo các danh mục con cho quần nam và quần nữ
          DB::table('categories')->insertGetId([
            'categoryName' => 'Quần Jeans',
            'description'  => 'Các loại quần jeans thời trang nam',
            'parentID'     => $qNam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
           DB::table('categories')->insertGetId([
            'categoryName' => 'Quần Dài',
            'description'  => 'Các loại quần dài thời trang nam',
            'parentID'     => $qNam,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
            DB::table('categories')->insertGetId([
                'categoryName' => 'Quần Short',
                'description'  => 'Các loại quần short thời trang nam',
                'parentID'     => $qNam,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
             DB::table('categories')->insertGetId([
                'categoryName' => 'Quần Jeans',
                'description'  => 'Các loại quần jeans thời trang nữ',
                'parentID'     => $qNu,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
            DB::table('categories')->insertGetId([
                'categoryName' => 'Quần Legging',
                'description'  => 'Các loại quần legging thời trang nữ',
                'parentID'     => $qNu,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
             DB::table('categories')->insertGetId([
                'categoryName' => 'Quần Short',
                'description'  => 'Các loại quần short thời trang nữ',
                'parentID'     => $qNu,
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ]);
               DB::table('categories')->insertGetId([
            'categoryName' => 'Quần Dài',
            'description'  => 'Các loại quần dài thời trang nữ',
            'parentID'     => $qNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        // Tạo các danh mục con cho váy nữ
         DB::table('categories')->insertGetId([
            'categoryName' => 'Váy Dài',
            'description'  => 'Các loại váy dài thời trang nữ',
            'parentID'     => $vNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
          DB::table('categories')->insertGetId([
            'categoryName' => 'Váy Ngắn',
            'description'  => 'Các loại váy ngắn thời trang nữ',
            'parentID'     => $vNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
           DB::table('categories')->insertGetId([
            'categoryName' => 'Váy Xòe',
            'description'  => 'Các loại váy xòe thời trang nữ',
            'parentID'     => $vNu,
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
        
    }
}
