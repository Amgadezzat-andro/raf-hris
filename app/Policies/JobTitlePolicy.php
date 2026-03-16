<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\JobTitle;

class JobTitlePolicy
{
    public function viewAny(Employee $employee): bool
    {
        return $employee->can('job_titles.view') || $employee->hasRole('super_admin');
    }

    public function create(Employee $employee): bool
    {
        return $employee->can('job_titles.create') || $employee->hasRole('super_admin');
    }

    public function update(Employee $employee, JobTitle $jobTitle): bool
    {
        return $employee->can('job_titles.update') || $employee->hasRole('super_admin');
    }
}
