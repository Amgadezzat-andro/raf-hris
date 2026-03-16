<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\HrCore\BranchController;
use App\Http\Controllers\Api\V1\HrCore\DepartmentController;
use App\Http\Controllers\Api\V1\HrCore\JobTitleController;
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

        Route::get('/branches', [BranchController::class, 'index']);
        Route::post('/branches', [BranchController::class, 'store']);
        Route::put('/branches/{branch}', [BranchController::class, 'update']);

        Route::get('/departments', [DepartmentController::class, 'index']);
        Route::post('/departments', [DepartmentController::class, 'store']);
        Route::put('/departments/{department}', [DepartmentController::class, 'update']);

        Route::get('/job-titles', [JobTitleController::class, 'index']);
        Route::post('/job-titles', [JobTitleController::class, 'store']);
        Route::put('/job-titles/{jobTitle}', [JobTitleController::class, 'update']);
    });
});
