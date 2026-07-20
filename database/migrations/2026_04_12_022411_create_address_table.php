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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id("addressID");
            $table->foreignId('userID')
                ->constrained('users', 'UserID')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('city', 200);
            $table->string('district', 200);
            $table->string('ward', 200);
            $table->text('addressDetail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
