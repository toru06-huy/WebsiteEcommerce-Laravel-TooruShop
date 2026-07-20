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
        Schema::create('cart', function (Blueprint $table) {
            $table->id('cartID');

            $table->foreignId('userID')
                ->constrained('users', 'UserID')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('variantID')
                ->constrained('product_variants', 'variantID')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->integer('quantity')->default(1);
            $table->boolean('abandoned_notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
