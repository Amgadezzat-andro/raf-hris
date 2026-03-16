<?php

namespace App\Actions\RbacAndScope;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SyncEmployeeBranchScopeAction
{
    public function execute(Employee $employee, array $branchIds): Employee
    {
        DB::transaction(function () use ($employee, $branchIds): void {
            $employee->branches()->delete();

            if ($branchIds !== []) {
                $employee->branches()->createMany(array_map(
                    fn (int $branchId): array => ['branch_id' => $branchId],
                    $branchIds,
                ));
            }
        });

        return $employee->load('branches', 'departments');
    }
}
