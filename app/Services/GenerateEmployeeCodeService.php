<?php

namespace App\Services;

use App\Models\Employee;

class GenerateEmployeeCodeService
{
    public function generate(): string
    {
        $lastCode = Employee::query()
            ->where('employee_code', 'like', 'EMP-%')
            ->orderByDesc('employee_code')
            ->value('employee_code');

        $lastNumber = 0;

        if (is_string($lastCode) && preg_match('/^EMP-(\d+)$/', $lastCode, $matches) === 1) {
            $lastNumber = (int) $matches[1];
        }

        do {
            $lastNumber++;
            $candidate = sprintf('EMP-%06d', $lastNumber);
        } while (Employee::query()->where('employee_code', $candidate)->exists());

        return $candidate;
    }
}
