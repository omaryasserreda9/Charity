<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('humanitarian_cases', 'area')) {
            Schema::table('humanitarian_cases', function (Blueprint $table): void {
                $table->string('area')->nullable()->after('national_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('humanitarian_cases', 'area')) {
            Schema::table('humanitarian_cases', function (Blueprint $table): void {
                $table->dropColumn('area');
            });
        }
    }
};
