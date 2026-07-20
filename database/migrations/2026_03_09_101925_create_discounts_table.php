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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id('discountID');
            $table->string('discountCode',50)->unique();
            $table->string('discountName',150);
            $table->enum('discountType',['percentage','fixedAmount']);
            $table->decimal('discountValue',10,2);
            $table->integer('discountLimit')->default(10);
            $table->dateTime('startDate')->nullable();
            $table->dateTime('endDate')->nullable();
            $table->decimal('minOrderValue',12,2)->default(0);
            $table->boolean('isActive')->default(true);
            $table->boolean('isPersonal')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};