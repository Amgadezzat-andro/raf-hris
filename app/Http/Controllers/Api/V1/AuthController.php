<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\GetCurrentEmployeeProfileAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RefreshTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RefreshTokenRequest;
use App\Http\Resources\Api\V1\Auth\AuthProfileResource;
use App\Http\Resources\Api\V1\Auth\AuthSessionResource;
use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginAction $loginAction,
        private readonly LogoutAction $logoutAction,
        private readonly RefreshTokenAction $refreshTokenAction,
        private readonly GetCurrentEmployeeProfileAction $profileAction,
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $payload = $this->loginAction->execute(
                email: $request->string('email')->toString(),
                password: $request->string('password')->toString(),
                deviceName: $request->string('device_name')->toString() ?: 'api-client',
            );
        } catch (AuthenticationException) {
            return ApiResponse::error('Invalid credentials.', 422);
        }

        return ApiResponse::success(new AuthSessionResource($payload));
    }

    public function logout(Request $request): JsonResponse
    {
        $employee = $request->user();
        $this->logoutAction->execute($employee);

        return ApiResponse::success([], 'Request completed successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        $employee = $request->user();
        $profile = $this->profileAction->execute($employee);

        return ApiResponse::success(new AuthProfileResource($profile));
    }

    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $employee = $request->user();
        $payload = $this->refreshTokenAction->execute(
            employee: $employee,
            deviceName: $request->string('device_name')->toString() ?: 'api-client',
        );

        return ApiResponse::success(new AuthSessionResource($payload));
    }
}
