<?php

namespace App\Actions\HrCore;

use App\Models\JobTitle;
use Illuminate\Support\Facades\DB;

class UpdateJobTitleAction
{
    public function execute(JobTitle $jobTitle, array $payload): JobTitle
    {
        return DB::transaction(function () use ($jobTitle, $payload): JobTitle {
            $jobTitle->update($payload);

            return $jobTitle->refresh();
        });
    }
}
