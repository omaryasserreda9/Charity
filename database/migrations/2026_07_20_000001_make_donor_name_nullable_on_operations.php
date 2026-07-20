<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('budget_operations', 'donor_name')) {
            DB::statement('ALTER TABLE budget_operations MODIFY donor_name VARCHAR(255) NULL');
        }

        if (Schema::hasColumn('inventory_operations', 'donor_name')) {
            DB::statement('ALTER TABLE inventory_operations MODIFY donor_name VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('budget_operations', 'donor_name')) {
            DB::statement('ALTER TABLE budget_operations MODIFY donor_name VARCHAR(255) NOT NULL');
        }

        if (Schema::hasColumn('inventory_operations', 'donor_name')) {
            DB::statement('ALTER TABLE inventory_operations MODIFY donor_name VARCHAR(255) NOT NULL');
        }
    }
};
