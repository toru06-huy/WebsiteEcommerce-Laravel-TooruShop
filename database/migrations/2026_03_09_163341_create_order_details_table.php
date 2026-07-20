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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('orderDetailID');
            $table->foreignId('orderID')
                  ->constrained('orders','orderID')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
             $table->foreignId('variantID')
                  ->constrained('product_variants','variantID')
                  ->cascadeOnUpdate();
            $table->integer('quantity');
            $table->decimal('unitPrice',12,2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
