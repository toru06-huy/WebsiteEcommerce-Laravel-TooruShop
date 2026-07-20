<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id('membershipID');
            $table->foreignId('userID')
                ->constrained('users', 'userID')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->enum('tier', ['Bronze', 'Silver', 'Gold', 'Platinum'])->default('Bronze');
            $table->decimal('totalSpent', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_tiers');
    }
};
