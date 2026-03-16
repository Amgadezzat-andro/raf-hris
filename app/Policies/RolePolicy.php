<?php

namespace App\Policies;

use App\Models\Employee;

class RolePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('roles.view') || $employee->hasRole('super_admin');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('roles.create') || $employee->hasRole('super_admin');
    }
}
