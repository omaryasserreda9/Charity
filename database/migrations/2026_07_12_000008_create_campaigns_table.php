<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('district_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->foreignId('campaign_category_id')->constrained()->restrictOnDelete();
            $table->enum('status', ['pending', 'done'])->default('pending');
            $table->date('campaign_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
