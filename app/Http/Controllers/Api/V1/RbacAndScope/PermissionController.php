<?php

namespace App\Http\Controllers\Api\V1\RbacAndScope;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RbacAndScope\IndexPermissionRequest;
use App\Http\Resources\Api\V1\RbacAndScope\PermissionResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(IndexPermissionRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Permission::class);

        $permissions = Permission::query()->orderBy('name')->paginate((int) $request->input('per_page', 20));

        return ApiResponse::paginated($permissions, PermissionResource::collection($permissions)->resolve());
    }
}
