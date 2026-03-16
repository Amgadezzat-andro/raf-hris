<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\Employee;

class DepartmentPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('departments.view') || $employee->hasRole('super_admin');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('departments.create') || $employee->hasRole('super_admin');
    }

    public function update(Employee $employee, Department $department): bool
    {
        return $employee->can('departments.update') || $employee->hasRole('super_admin');
    }
}
