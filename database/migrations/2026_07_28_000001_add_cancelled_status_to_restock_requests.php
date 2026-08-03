<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cập nhật ENUM cho cột status
        DB::statement("ALTER TABLE restock_requests MODIFY status ENUM('pending', 'supplier_confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");

        // 2. Bước 1: Thêm các cột nhận hàng trước
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('receivedBy')->nullable()->after('confirmedAt');
            $table->timestamp('receivedAt')->nullable()->after('receivedBy');
        });

        // 3. Bước 2: Thêm các cột hủy hàng (lúc này cột 'receivedAt' đã tồn tại trong DB)
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->text('cancelReason')->nullable()->after('receivedAt');
            $table->enum('cancelledByType', ['supplier', 'staff'])->nullable()->after('cancelReason');
            $table->unsignedBigInteger('cancelledByUserID')->nullable()->after('cancelledByType');
            $table->timestamp('cancelledAt')->nullable()->after('cancelledByUserID');
        });
    }

    public function down(): void
    {
        // Xóa tất cả các cột đã thêm
        Schema::table('restock_requests', function (Blueprint $table) {
            $table->dropColumn([
                'receivedBy',
                'receivedAt',
                'cancelReason',
                'cancelledByType',
                'cancelledByUserID',
                'cancelledAt',
            ]);
        });

        // Trả lại ENUM status ban đầu
        DB::statement("ALTER TABLE restock_requests MODIFY status ENUM('pending', 'confirmed') NOT NULL DEFAULT 'pending'");
    }
};