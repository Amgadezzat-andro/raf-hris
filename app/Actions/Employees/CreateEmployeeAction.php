<?php

namespace App\Actions\Employees;

use App\Models\Employee;
use App\Services\GenerateEmployeeCodeService;
use Illuminate\Support\Facades\DB;

class CreateEmployeeAction
{
    public function __construct(
        private readonly GenerateEmployeeCodeService $generateEmployeeCodeService,
    ) {
    }

    public function execute(array $payload): Employee
    {
        return DB::transaction(function () use ($payload): Employee {
            $payload['employee_code'] = $this->generateEmployeeCodeService->generate();

            return Employee::query()->create($payload);
        });
    }
}
