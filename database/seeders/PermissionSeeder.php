<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'roles.view',
            'roles.create',
            'permissions.view',
            'employees.assign_roles',
            'employees.manage_scope',
            'employees.view',
            'employees.create',
            'employees.update',
            'contracts.view',
            'contracts.create',
            'contracts.update',
            'branches.view',
            'branches.create',
            'branches.update',
            'departments.view',
            'departments.create',
            'departments.update',
            'job_titles.view',
            'job_titles.create',
            'job_titles.update',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
