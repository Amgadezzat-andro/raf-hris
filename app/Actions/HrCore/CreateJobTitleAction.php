<?php

namespace App\Actions\HrCore;

use App\Models\JobTitle;
use Illuminate\Support\Facades\DB;

class CreateJobTitleAction
{
    public function execute(array $payload): JobTitle
    {
        return DB::transaction(fn () => JobTitle::query()->create($payload));
    }
}
