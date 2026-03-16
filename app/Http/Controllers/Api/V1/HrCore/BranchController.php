<?php

namespace App\Http\Controllers\Api\V1\HrCore;

use App\Actions\HrCore\CreateBranchAction;
use App\Actions\HrCore\UpdateBranchAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\HrCore\IndexBranchRequest;
use App\Http\Requests\Api\V1\HrCore\StoreBranchRequest;
use App\Http\Requests\Api\V1\HrCore\UpdateBranchRequest;
use App\Http\Resources\Api\V1\HrCore\BranchResource;
use App\Models\Branch;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class BranchController extends Controller
{
    public function __construct(
        private readonly CreateBranchAction $createBranchAction,
        private readonly UpdateBranchAction $updateBranchAction,
    ) {
    }

    public function index(IndexBranchRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Branch::class);

        $query = Branch::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($inner) use ($search): void {
                $inner->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $branches = $query->orderBy('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($branches, BranchResource::collection($branches)->resolve());
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        Gate::authorize('create', Branch::class);

        $branch = $this->createBranchAction->execute($request->validated());

        return ApiResponse::success(new BranchResource($branch));
    }

    public function update(UpdateBranchRequest $request, Branch $branch): JsonResponse
    {
        Gate::authorize('update', $branch);

        $branch = $this->updateBranchAction->execute($branch, $request->validated());

        return ApiResponse::success(new BranchResource($branch));
    }
}
