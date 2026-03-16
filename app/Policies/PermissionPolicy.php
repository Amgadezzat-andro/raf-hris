<?php

namespace App\Policies;

use App\Models\Employee;

class PermissionPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('permissions.view') || $employee->hasRole('super_admin');
    }
}
