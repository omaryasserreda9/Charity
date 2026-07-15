<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_case_referrer', function (Blueprint $table): void {
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('case_referrer_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['campaign_id', 'case_referrer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_case_referrer');
    }
};
