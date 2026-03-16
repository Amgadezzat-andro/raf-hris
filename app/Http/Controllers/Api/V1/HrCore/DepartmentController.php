<?php

namespace App\Http\Controllers\Api\V1\HrCore;

use App\Actions\HrCore\CreateDepartmentAction;
use App\Actions\HrCore\UpdateDepartmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\HrCore\IndexDepartmentRequest;
use App\Http\Requests\Api\V1\HrCore\StoreDepartmentRequest;
use App\Http\Requests\Api\V1\HrCore\UpdateDepartmentRequest;
use App\Http\Resources\Api\V1\HrCore\DepartmentResource;
use App\Models\Department;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    public function __construct(
        private readonly CreateDepartmentAction $createDepartmentAction,
        private readonly UpdateDepartmentAction $updateDepartmentAction,
    ) {
    }

    public function index(IndexDepartmentRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Department::class);

        $query = Department::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->integer('branch_id'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
        }

        $departments = $query->orderBy('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($departments, DepartmentResource::collection($departments)->resolve());
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        Gate::authorize('create', Department::class);

        $department = $this->createDepartmentAction->execute($request->validated());

        return ApiResponse::success(new DepartmentResource($department));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        Gate::authorize('update', $department);

        $department = $this->updateDepartmentAction->execute($department, $request->validated());

        return ApiResponse::success(new DepartmentResource($department));
    }
}
