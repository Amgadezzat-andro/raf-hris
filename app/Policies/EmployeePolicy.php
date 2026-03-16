<?php

namespace App\Policies;

use App\Models\Employee;

class EmployeePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('employees.view') || $employee->hasRole('super_admin');
    }

    public function view(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.view') || $employee->hasRole('super_admin') || $employee->is($target);
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('employees.create') || $employee->hasRole('super_admin');
    }

    public function update(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.update') || $employee->hasRole('super_admin');
    }

    public function assignRoles(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.assign_roles') || $employee->hasRole('super_admin');
    }

    public function manageScope(Employee $employee, Employee $target): bool
    {
        return $employee->can('employees.manage_scope') || $employee->hasRole('super_admin');
    }
}
