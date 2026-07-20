<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create donors table
        Schema::create('donors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->foreignId('charity_home_id')->nullable()->constrained('charity_homes')->nullOnDelete();
            $table->timestamps();
        });

        // 2. Add donor_id foreign key to budget_operations
        Schema::table('budget_operations', function (Blueprint $table): void {
            $table->foreignId('donor_id')->nullable()->after('budget_category_id')->constrained('donors')->nullOnDelete();
        });

        // 3. Add donor_id foreign key to inventory_operations
        Schema::table('inventory_operations', function (Blueprint $table): void {
            $table->foreignId('donor_id')->nullable()->after('inventory_category_id')->constrained('donors')->nullOnDelete();
        });

        // 4. Create and assign permissions for the donors module
        $permissions = [
            ['name' => 'donors.view', 'description' => 'View Donors', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'donors.add', 'description' => 'Add Donors', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'donors.edit', 'description' => 'Edit Donors', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'donors.delete', 'description' => 'Delete Donors', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($permissions as $perm) {
            DB::table('permissions')->updateOrInsert(['name' => $perm['name']], $perm);
        }

        $permIds = DB::table('permissions')
            ->whereIn('name', ['donors.view', 'donors.add', 'donors.edit', 'donors.delete'])
            ->pluck('id');

        $roles = DB::table('roles')
            ->whereIn('name', ['Super Admin', 'Charity Home Manager'])
            ->get();

        foreach ($roles as $role) {
            foreach ($permIds as $permId) {
                DB::table('permission_role')->updateOrInsert([
                    'permission_id' => $permId,
                    'role_id' => $role->id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_operations', function (Blueprint $table): void {
            $table->dropForeign(['donor_id']);
            $table->dropColumn('donor_id');
        });

        Schema::table('budget_operations', function (Blueprint $table): void {
            $table->dropForeign(['donor_id']);
            $table->dropColumn('donor_id');
        });

        Schema::dropIfExists('donors');

        $permIds = DB::table('permissions')
            ->whereIn('name', ['donors.view', 'donors.add', 'donors.edit', 'donors.delete'])
            ->pluck('id');

        DB::table('permission_role')->whereIn('permission_id', $permIds)->delete();
        DB::table('permissions')->whereIn('id', $permIds)->delete();
    }
};
