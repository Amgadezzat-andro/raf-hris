<?php

namespace App\Actions\RbacAndScope;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateRoleAction
{
    public function execute(string $name, array $permissions = []): Role
    {
        /** @var Role $role */
        $role = DB::transaction(function () use ($name, $permissions): Role {
            $role = Role::findOrCreate($name, 'web');
            $role->syncPermissions($permissions);

            return $role->load('permissions');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $role;
    }
}
