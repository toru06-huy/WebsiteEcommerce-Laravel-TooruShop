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
        Schema::create('user_discounts', function (Blueprint $table) {
            $table->id('userDiscountID');

            $table->foreignId('userID')
                ->constrained('users', 'userID');

            $table->foreignId('discountID')
                ->constrained('discounts', 'discountID');

            $table->boolean('isUsed')->default(false);

            $table->dateTime('usedAt')->nullable();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_discounts');
    }
};
