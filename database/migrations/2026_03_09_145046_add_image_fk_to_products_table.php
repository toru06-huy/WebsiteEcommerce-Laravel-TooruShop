<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration riêng để thêm FK imageID vào products SAU KHI product_images đã tồn tại.
 * Tách ra để tránh circular dependency:
 *   products → product_images → products
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('imageID')
                  ->references('imageID')
                  ->on('product_images')
                  ->nullOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['imageID']);
        });
    }
};
