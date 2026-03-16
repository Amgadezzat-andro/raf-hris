<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Employees\CreateEmployeeAction;
use App\Actions\Employees\UpdateEmployeeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Employees\IndexEmployeeRequest;
use App\Http\Requests\Api\V1\Employees\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\Employees\UpdateEmployeeRequest;
use App\Http\Resources\Api\V1\Employees\EmployeeDetailResource;
use App\Http\Resources\Api\V1\Employees\EmployeeSummaryResource;
use App\Models\Employee;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly CreateEmployeeAction $createEmployeeAction,
        private readonly UpdateEmployeeAction $updateEmployeeAction,
    ) {
    }

    public function index(IndexEmployeeRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Employee::class);

        /** @var Employee $actor */
        $actor = $request->user();

        $query = Employee::query()->visibleTo($actor);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->integer('branch_id'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($inner) use ($search): void {
                $inner->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($employees, EmployeeSummaryResource::collection($employees)->resolve());
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        Gate::authorize('create', Employee::class);

        $employee = $this->createEmployeeAction->execute($request->validated());

        return ApiResponse::success(new EmployeeDetailResource($employee));
    }

    public function show(Employee $employee): JsonResponse
    {
        Gate::authorize('view', $employee);

        return ApiResponse::success(new EmployeeDetailResource($employee));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('update', $employee);

        $employee = $this->updateEmployeeAction->execute($employee, $request->validated());

        return ApiResponse::success(new EmployeeDetailResource($employee));
    }
}
