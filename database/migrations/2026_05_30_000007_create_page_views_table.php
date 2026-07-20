<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id('viewID');

            // null = khách vãng lai
            $table->foreignId('userID')
                  ->nullable()
                  ->constrained('users', 'userID')
                  ->nullOnDelete();

            $table->string('session_id', 100)->index(); // định danh khách vãng lai
            $table->string('ip', 45)->nullable();
            $table->string('path', 500)->nullable();    // URL đã truy cập
            $table->string('user_agent', 500)->nullable();

            // Chỉ lưu timestamp tạo, không cần updated_at
            $table->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
