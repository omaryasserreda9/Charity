<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            $table->foreignId('referrer_id')->nullable()->constrained('case_referrers')->nullOnDelete();
            $table->string('research_team')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            $table->dropForeign(['referrer_id']);
            $table->dropColumn('referrer_id');
            $table->dropColumn('research_team');
        });
    }
};
