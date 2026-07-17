<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charity_homes', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });

        DB::table('charity_homes')->insert([
            'id' => 1,
            'title' => 'Primary Charity Home',
        ]);

        $tables = [
            'humanitarian_cases',
            'budget_categories',
            'budget_operations',
            'inventory_categories',
            'inventory_operations',
            'districts',
            'campaign_categories',
            'campaigns',
            'campaign_operations',
            'humanitarian_case_files',
            'family_members',
            'case_incomes',
            'case_expenses',
            'case_home_descriptions',
            'case_needs',
            'case_referrers',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'charity_home_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->foreignId('charity_home_id')->nullable()->constrained('charity_homes')->nullOnDelete();
                });
            }
        }

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                DB::table($tableName)->update(['charity_home_id' => 1]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'humanitarian_cases',
            'budget_categories',
            'budget_operations',
            'inventory_categories',
            'inventory_operations',
            'districts',
            'campaign_categories',
            'campaigns',
            'campaign_operations',
            'humanitarian_case_files',
            'family_members',
            'case_incomes',
            'case_expenses',
            'case_home_descriptions',
            'case_needs',
            'case_referrers',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'charity_home_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->dropForeign(['charity_home_id']);
                    $table->dropColumn('charity_home_id');
                });
            }
        }

        Schema::dropIfExists('charity_homes');
    }
};
