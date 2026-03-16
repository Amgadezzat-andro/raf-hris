<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\Employee;

class BranchPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('branches.view') || $employee->hasRole('super_admin');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('branches.create') || $employee->hasRole('super_admin');
    }

    public function update(Employee $employee, Branch $branch): bool
    {
        return $employee->can('branches.update') || $employee->hasRole('super_admin');
    }
}
