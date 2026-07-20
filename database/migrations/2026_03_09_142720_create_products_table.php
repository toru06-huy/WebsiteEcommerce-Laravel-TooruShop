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
        Schema::create('products', function (Blueprint $table) {
            $table->id('productID');
            $table->string('productName',200);
            $table->foreignId('categoryID')
                  ->constrained('categories','categoryID')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('manufacturerID')
                  ->constrained('manufacturers','manufacturerID')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->decimal('basePrice',12,2);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('imageID')->nullable();          
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
