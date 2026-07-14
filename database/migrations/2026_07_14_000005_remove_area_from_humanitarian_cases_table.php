<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            if (Schema::hasColumn('humanitarian_cases', 'area')) {
                $table->dropColumn('area');
            }
        });
    }

    public function down(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            if (! Schema::hasColumn('humanitarian_cases', 'area')) {
                $table->string('area')->nullable()->after('national_id');
            }
        });
    }
};
