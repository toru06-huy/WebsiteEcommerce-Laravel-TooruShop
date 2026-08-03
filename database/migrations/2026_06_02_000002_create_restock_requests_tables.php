<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restock_requests', function (Blueprint $table) {
            $table->id('restockRequestID');

            $table->unsignedBigInteger('productID');
            $table->unsignedBigInteger('manufacturerID');

            $table->string('token', 64)->unique();
            $table->enum('status', [
                'pending',
                'supplier_confirmed',
                'completed'
            ])->default('pending');

            $table->unsignedBigInteger('requestedBy')->nullable();
            // Nhà cung cấp xác nhận
            $table->timestamp('confirmedAt')->nullable();
            // Nhân viên xác nhận nhập kho
            $table->unsignedBigInteger('receivedBy')->nullable();
            $table->timestamp('receivedAt')->nullable();

            $table->timestamps();

            $table->foreign('productID')
                ->references('productID')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('manufacturerID')
                ->references('manufacturerID')
                ->on('manufacturers')
                ->onDelete('cascade');
        });

        Schema::create('restock_request_items', function (Blueprint $table) {
            $table->id('itemID');

            $table->unsignedBigInteger('restockRequestID');
            $table->unsignedBigInteger('variantID');
            $table->unsignedInteger('quantity');

            $table->timestamps();

            $table->foreign('restockRequestID')
                ->references('restockRequestID')
                ->on('restock_requests')
                ->onDelete('cascade');

            $table->foreign('variantID')
                ->references('variantID')
                ->on('product_variants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restock_request_items');
        Schema::dropIfExists('restock_requests');
    }
};