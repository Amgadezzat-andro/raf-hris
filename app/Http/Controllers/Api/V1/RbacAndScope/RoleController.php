<?php

namespace App\Http\Controllers\Api\V1\RbacAndScope;

use App\Actions\RbacAndScope\CreateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RbacAndScope\IndexRoleRequest;
use App\Http\Requests\Api\V1\RbacAndScope\StoreRoleRequest;
use App\Http\Resources\Api\V1\RbacAndScope\RoleResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(private readonly CreateRoleAction $createRoleAction)
    {
    }

    public function index(IndexRoleRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Role::class);

        $query = Role::query()->with('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
        }

        $roles = $query->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($roles, RoleResource::collection($roles)->resolve());
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        Gate::authorize('create', Role::class);

        $role = $this->createRoleAction->execute(
            $request->string('name')->toString(),
            $request->input('permissions', []),
        );

        return ApiResponse::success(new RoleResource($role));
    }
}
