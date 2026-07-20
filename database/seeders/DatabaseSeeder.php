<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── 2. Lookup tables (không phụ thuộc nhau) ─────────────────────
        $this->call([
            SizeSeeder::class,
            ColorSeeder::class,
            PositionSeeder::class,
            CategorySeeder::class,
            ManufacturerSeeder::class,
            DiscountSeeder::class,
        ]);

        // ── 3. Users: Employee + Customer ────────────────────────────────
        $this->call([
            EmployeeSeeder::class,
            CustomerSeeder::class,
        ]);

        // ── 4. Products (tự tạo product_images + gán imageID bên trong) ─
        //    Thứ tự: ProductSeeder xử lý luôn circular dependency
        $this->call([
            ProductSeeder::class,        // tạo product → tạo image → update imageID
            ProductVariantSeeder::class, // product_variants
            FixProductCoverSeeder::class,  // gán imageID cho product nếu chưa có
        ]);

        // ── 5. Orders ────────────────────────────────────────────────────
        $this->call([
            OrderSeeder::class,
            OrderDetailSeeder::class,
        ]);
    
    }
}
