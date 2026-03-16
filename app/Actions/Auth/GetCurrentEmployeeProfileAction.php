<?php

namespace App\Actions\Auth;

use App\Models\Employee;

class GetCurrentEmployeeProfileAction
{
    public function execute(Employee $employee): Employee
    {
        return $employee->loadMissing('roles', 'permissions', 'branches', 'departments');
    }
}
