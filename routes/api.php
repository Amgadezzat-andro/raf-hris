<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\RbacAndScope\EmployeeRoleController;
use App\Http\Controllers\Api\V1\RbacAndScope\EmployeeScopeController;
use App\Http\Controllers\Api\V1\RbacAndScope\PermissionController;
use App\Http\Controllers\Api\V1\RbacAndScope\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/health', HealthController::class);

    Route::prefix('auth')->group(function (): void {
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::prefix('auth')->group(function (): void {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/refresh', [AuthController::class, 'refresh']);
        });

        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/permissions', [PermissionController::class, 'index']);
        Route::post('/employees/{employee}/roles', [EmployeeRoleController::class, 'store']);
        Route::put('/employees/{employee}/branches/sync', [EmployeeScopeController::class, 'syncBranches']);
        Route::put('/employees/{employee}/departments/sync', [EmployeeScopeController::class, 'syncDepartments']);
    });
});
