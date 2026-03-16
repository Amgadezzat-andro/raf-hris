<?php

namespace App\Actions\Employees;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class UpdateEmployeeAction
{
    public function execute(Employee $employee, array $payload): Employee
    {
        return DB::transaction(function () use ($employee, $payload): Employee {
            $employee->update($payload);

            return $employee->refresh();
        });
    }
}
