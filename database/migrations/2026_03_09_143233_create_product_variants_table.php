<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id('variantID');
            $table->foreignId('productID')
                  ->constrained('products', 'productID')
                  ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sizeID')
                  ->constrained('sizes', 'sizeID')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('colorID')
                  ->constrained('colors', 'colorID')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->integer('stockQuantity')->default(0);

            $table->timestamps();

            $table->unique(['productID', 'sizeID', 'colorID'], 'variants_unique_index');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
