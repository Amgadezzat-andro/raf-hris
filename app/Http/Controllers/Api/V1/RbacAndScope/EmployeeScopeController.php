<?php

namespace App\Http\Controllers\Api\V1\RbacAndScope;

use App\Actions\RbacAndScope\SyncEmployeeBranchScopeAction;
use App\Actions\RbacAndScope\SyncEmployeeDepartmentScopeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RbacAndScope\SyncEmployeeBranchesRequest;
use App\Http\Requests\Api\V1\RbacAndScope\SyncEmployeeDepartmentsRequest;
use App\Http\Resources\Api\V1\RbacAndScope\EmployeeScopeResource;
use App\Models\Employee;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class EmployeeScopeController extends Controller
{
    public function __construct(
        private readonly SyncEmployeeBranchScopeAction $syncEmployeeBranchScopeAction,
        private readonly SyncEmployeeDepartmentScopeAction $syncEmployeeDepartmentScopeAction,
    ) {
    }

    public function syncBranches(SyncEmployeeBranchesRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('manageScope', $employee);

        $employee = $this->syncEmployeeBranchScopeAction->execute($employee, $request->input('branch_ids', []));

        return ApiResponse::success(new EmployeeScopeResource($employee));
    }

    public function syncDepartments(SyncEmployeeDepartmentsRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('manageScope', $employee);

        $employee = $this->syncEmployeeDepartmentScopeAction->execute($employee, $request->input('department_ids', []));

        return ApiResponse::success(new EmployeeScopeResource($employee));
    }
}
