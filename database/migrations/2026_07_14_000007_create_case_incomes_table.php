<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_incomes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('humanitarian_case_id')->constrained('humanitarian_cases')->restrictOnDelete()->unique();
            $table->decimal('job_income', 10, 2)->nullable();
            $table->decimal('pension_income', 10, 2)->nullable();
            $table->decimal('charity_income', 10, 2)->nullable();
            $table->decimal('other_income', 10, 2)->nullable();
            $table->decimal('total_income', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_incomes');
    }
};
