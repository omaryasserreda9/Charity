<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    private $modules = [
        'dashboard',
        'budget_categories',
        'budget_operations',
        'inventory_categories',
        'inventory_operations',
        'humanitarian_cases',
        'case_referrers',
        'family_members',
        'case_incomes',
        'case_expenses',
        'case_home_descriptions',
        'case_needs',
        'districts',
        'campaign_categories',
        'campaigns',
        'humanitarian_case_files',
        'charity_homes',
        'users',
    ];

    private $actions = [
        'view',
        'add',
        'edit',
        'delete',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionNames = [];

        foreach ($this->modules as $module) {
            foreach ($this->actions as $action) {
                $permissionNames[] = "{$module}.{$action}";
            }
        }

        $permissions = [];

        foreach ($permissionNames as $permissionName) {
            $permissions[] = Permission::firstOrCreate(
                ['name' => $permissionName],
                [
                    'description' => ucfirst(str_replace(['_', '.'], [' ', ' '], $permissionName)),
                ]
            );
        }

        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'description' => 'Full access to all system permissions.',
            ]
        );

        $superAdminRole->permissions()->sync(collect($permissions)->pluck('id')->all());

        $superAdminUser = User::find(1);

        if ($superAdminUser) {
            $superAdminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}