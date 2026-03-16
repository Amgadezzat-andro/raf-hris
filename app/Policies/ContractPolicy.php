<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\Employee;

class ContractPolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('contracts.view') || $employee->hasRole('super_admin');
    }

    public function view(Employee $employee, Contract $contract): bool
    {
        return $employee->can('contracts.view') || $employee->hasRole('super_admin');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('contracts.create') || $employee->hasRole('super_admin');
    }

    public function update(Employee $employee, Contract $contract): bool
    {
        return $employee->can('contracts.update') || $employee->hasRole('super_admin');
    }
}
