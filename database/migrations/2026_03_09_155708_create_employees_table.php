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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employeeID');
            $table->foreignId('userID')->unique()
                  ->constrained('users','userID')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('employeeCode',20)->unique();
            $table->foreignId('positionID')
                  ->constrained('positions','positionID')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->decimal('salary',12,2);
            $table->date('hireDate')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
