<?php

namespace App\Actions\Auth;

use App\Models\Employee;

class LogoutAction
{
    public function execute(Employee $employee): void
    {
        $token = $employee->currentAccessToken();

        if ($token !== null && isset($token->id)) {
            $employee->tokens()->whereKey($token->id)->delete();
        }
    }
}
