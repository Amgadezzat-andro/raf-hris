<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $allPermissions = Permission::query()->pluck('name')->all();

        Role::findByName('super_admin', 'web')->syncPermissions($allPermissions);

        Role::findByName('employee', 'web')->syncPermissions([
            'employees.view',
        ]);

        Role::findByName('organization_admin', 'web')->syncPermissions([
            'roles.view',
            'roles.create',
            'permissions.view',
            'employees.assign_roles',
            'employees.manage_scope',
            'branches.view',
            'branches.create',
            'branches.update',
            'departments.view',
            'departments.create',
            'departments.update',
            'job_titles.view',
            'job_titles.create',
            'job_titles.update',
        ]);
    }
}
