<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id('wishlistID');
            $table->foreignId('userID')
                  ->constrained('users', 'userID')
                  ->cascadeOnDelete();
            $table->foreignId('productID')
                  ->constrained('products', 'productID')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['userID', 'productID']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
