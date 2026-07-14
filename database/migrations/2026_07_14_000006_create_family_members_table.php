<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('humanitarian_case_id')->constrained('humanitarian_cases')->restrictOnDelete();
            $table->string('name')->nullable();
            $table->string('relation')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('education')->nullable();
            $table->string('health_status')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('average_income')->nullable();
            $table->string('job')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
