<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Contracts\CreateContractAction;
use App\Actions\Contracts\UpdateContractAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Contracts\IndexContractRequest;
use App\Http\Requests\Api\V1\Contracts\IndexEmployeeContractRequest;
use App\Http\Requests\Api\V1\Contracts\StoreContractRequest;
use App\Http\Requests\Api\V1\Contracts\UpdateContractRequest;
use App\Http\Resources\Api\V1\Contracts\ContractResource;
use App\Models\Contract;
use App\Models\Employee;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ContractController extends Controller
{
    public function __construct(
        private readonly CreateContractAction $createContractAction,
        private readonly UpdateContractAction $updateContractAction,
    ) {
    }

    public function index(IndexContractRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Contract::class);

        $query = Contract::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->integer('employee_id'));
        }

        $contracts = $query->orderByDesc('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($contracts, ContractResource::collection($contracts)->resolve());
    }

    public function indexByEmployee(IndexEmployeeContractRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('viewAny', Contract::class);

        $query = Contract::query()->where('employee_id', $employee->id);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $contracts = $query->orderByDesc('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($contracts, ContractResource::collection($contracts)->resolve());
    }

    public function storeForEmployee(StoreContractRequest $request, Employee $employee): JsonResponse
    {
        Gate::authorize('create', Contract::class);

        $contract = $this->createContractAction->execute($employee, $request->validated());

        return ApiResponse::success(new ContractResource($contract));
    }

    public function show(Contract $contract): JsonResponse
    {
        Gate::authorize('view', $contract);

        return ApiResponse::success(new ContractResource($contract));
    }

    public function update(UpdateContractRequest $request, Contract $contract): JsonResponse
    {
        Gate::authorize('update', $contract);

        $contract = $this->updateContractAction->execute($contract, $request->validated());

        return ApiResponse::success(new ContractResource($contract));
    }
}
