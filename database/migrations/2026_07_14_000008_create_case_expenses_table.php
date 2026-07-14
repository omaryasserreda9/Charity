<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_expenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('humanitarian_case_id')->constrained('humanitarian_cases')->restrictOnDelete()->unique();
            $table->decimal('home_rent', 10, 2)->nullable();
            $table->decimal('school_expenses', 10, 2)->nullable();
            $table->decimal('utilities', 10, 2)->nullable();
            $table->decimal('medicine', 10, 2)->nullable();
            $table->decimal('nutrition', 10, 2)->nullable();
            $table->decimal('other_expenses', 10, 2)->nullable();
            $table->decimal('total_expenses', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_expenses');
    }
};
