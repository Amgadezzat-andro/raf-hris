<?php

namespace App\Policies;

use App\Models\Employee;

class EmployeePolicy
{
    public function assignRoles(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.assign_roles') || $employee->hasRole('super_admin');
    }

    public function manageScope(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.manage_scope') || $employee->hasRole('super_admin');
    }
}
