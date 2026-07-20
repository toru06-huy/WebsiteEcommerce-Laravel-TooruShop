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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->foreignId('userID')->nullable()->constrained('users', 'userID')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('orderDate')->useCurrent();
            $table->decimal('totalAmount', 12, 2);
            $table->foreignId('discountID')->nullable()->constrained('discounts', 'discountID')
                ->cascadeOnUpdate()->nullOnDelete();
            $table->decimal('discountAmount', 12, 2)->default(0);
            $table->decimal('finalAmount', 12, 2);
            $table->enum('status', [
                'Pending',
                'CancelRequested',
                'Confirmed',
                'Shipping',
                'Completed',
                'Cancelled'
            ])->default('Pending');
            $table->text('shippingAddress')->nullable();

            $table->text('payment')->nullable();
            
            $table->foreignId('processedBy')
                ->nullable()
                ->constrained('employees', 'employeeID')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
