<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_needs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('humanitarian_case_id')->constrained('humanitarian_cases')->restrictOnDelete()->unique();
            $table->text('requested_needs')->nullable();
            $table->text('recommended_needs')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_needs');
    }
};
