<?php

namespace App\Actions\RbacAndScope;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SyncEmployeeDepartmentScopeAction
{
    public function execute(Employee $employee, array $departmentIds): Employee
    {
        DB::transaction(function () use ($employee, $departmentIds): void {
            $employee->departments()->delete();

            if ($departmentIds !== []) {
                $employee->departments()->createMany(array_map(
                    fn (int $departmentId): array => ['department_id' => $departmentId],
                    $departmentIds,
                ));
            }
        });

        return $employee->load('branches', 'departments');
    }
}
