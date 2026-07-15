<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budget_operations', function (Blueprint $table): void {
            if (! Schema::hasColumn('budget_operations', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('donor_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('budget_operations', function (Blueprint $table): void {
            if (Schema::hasColumn('budget_operations', 'receipt_number')) {
                $table->dropColumn('receipt_number');
            }
        });
    }
};
