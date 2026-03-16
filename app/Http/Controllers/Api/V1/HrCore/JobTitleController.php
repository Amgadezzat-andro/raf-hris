<?php

namespace App\Http\Controllers\Api\V1\HrCore;

use App\Actions\HrCore\CreateJobTitleAction;
use App\Actions\HrCore\UpdateJobTitleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\HrCore\IndexJobTitleRequest;
use App\Http\Requests\Api\V1\HrCore\StoreJobTitleRequest;
use App\Http\Requests\Api\V1\HrCore\UpdateJobTitleRequest;
use App\Http\Resources\Api\V1\HrCore\JobTitleResource;
use App\Models\JobTitle;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class JobTitleController extends Controller
{
    public function __construct(
        private readonly CreateJobTitleAction $createJobTitleAction,
        private readonly UpdateJobTitleAction $updateJobTitleAction,
    ) {
    }

    public function index(IndexJobTitleRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', JobTitle::class);

        $query = JobTitle::query();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->integer('department_id'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
        }

        $jobTitles = $query->orderBy('id')->paginate($request->integer('per_page', 20));

        return ApiResponse::paginated($jobTitles, JobTitleResource::collection($jobTitles)->resolve());
    }

    public function store(StoreJobTitleRequest $request): JsonResponse
    {
        Gate::authorize('create', JobTitle::class);

        $jobTitle = $this->createJobTitleAction->execute($request->validated());

        return ApiResponse::success(new JobTitleResource($jobTitle));
    }

    public function update(UpdateJobTitleRequest $request, JobTitle $jobTitle): JsonResponse
    {
        Gate::authorize('update', $jobTitle);

        $jobTitle = $this->updateJobTitleAction->execute($jobTitle, $request->validated());

        return ApiResponse::success(new JobTitleResource($jobTitle));
    }
}
