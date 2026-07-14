<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            if (! Schema::hasColumn('humanitarian_cases', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('id');
                $table->foreign('district_id')->references('id')->on('districts')->restrictOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('humanitarian_cases', function (Blueprint $table): void {
            if (Schema::hasColumn('humanitarian_cases', 'district_id')) {
                $table->dropForeign(['district_id']);
                $table->dropColumn('district_id');
            }
        });
    }
};
