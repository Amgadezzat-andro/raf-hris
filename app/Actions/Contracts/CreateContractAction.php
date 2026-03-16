<?php

namespace App\Actions\Contracts;

use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class CreateContractAction
{
    public function execute(Employee $employee, array $payload): Contract
    {
        return DB::transaction(fn () => Contract::query()->create([
            ...$payload,
            'employee_id' => $employee->id,
        ]));
    }
}
