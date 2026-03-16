<?php

namespace App\Actions\Auth;

use App\Models\Employee;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    public function execute(string $email, string $password, string $deviceName = 'api-client'): array
    {
        /** @var Employee|null $employee */
        $employee = Employee::query()->where('email', $email)->first();

        if (! $employee || ! Hash::check($password, $employee->password)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        if (in_array($employee->status, ['inactive', 'suspended', 'offboarded'], true)) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $employee->loadMissing('roles', 'permissions');

        return [
            'token' => $employee->createToken($deviceName)->plainTextToken,
            'employee' => [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'status' => $employee->status,
            ],
        ];
    }
}
