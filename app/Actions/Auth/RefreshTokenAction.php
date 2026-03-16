<?php

namespace App\Actions\Auth;

use App\Models\Employee;

class RefreshTokenAction
{
    public function execute(Employee $employee, string $deviceName = 'api-client'): array
    {
        $token = $employee->currentAccessToken();

        if ($token !== null && isset($token->id)) {
            $employee->tokens()->whereKey($token->id)->delete();
        }

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
