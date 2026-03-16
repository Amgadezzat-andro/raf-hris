<?php

namespace App\Http\Controllers\Api\V1\RbacAndScope;

use App\Actions\RbacAndScope\AssignRolesToEmployeeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RbacAndScope\AssignEmployeeRolesRequest;
use App\Http\Resources\Api\V1\RbacAndScope\EmployeeRoleAssignmentResource;
use App\Models\Employee;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class EmployeeRoleController extends Controller
{
    public function __construct(private readonly AssignRolesToEmployeeAction $assignRolesToEmployeeAction)
    {
    }

    public function store(AssignEmployeeRolesRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('assignRoles', $employee);

        $employee = $this->assignRolesToEmployeeAction->execute($employee, $request->input('roles', []));

        return ApiResponse::success(new EmployeeRoleAssignmentResource($employee));
    }
}
