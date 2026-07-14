<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('case_home_descriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('humanitarian_case_id')->constrained('humanitarian_cases')->restrictOnDelete()->unique();
            $table->unsignedTinyInteger('rooms_count')->nullable();
            $table->boolean('clean_water')->nullable();
            $table->string('roof_condition')->nullable();
            $table->string('flooring_type')->nullable();
            $table->boolean('has_tv')->nullable();
            $table->boolean('has_washing_machine')->nullable();
            $table->boolean('has_gas_stove')->nullable();
            $table->boolean('has_fan')->nullable();
            $table->boolean('has_phone')->nullable();
            $table->boolean('has_fridge')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_home_descriptions');
    }
};
