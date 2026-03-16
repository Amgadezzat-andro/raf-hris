<?php

namespace App\Actions\RbacAndScope;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class AssignRolesToEmployeeAction
{
    public function execute(Employee $employee, array $roles): Employee
    {
        DB::transaction(function () use ($employee, $roles): void {
            $employee->syncRoles($roles);
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $employee->load('roles', 'permissions');
    }
}
