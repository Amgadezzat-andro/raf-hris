<?php

namespace App\Actions\HrCore;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class CreateBranchAction
{
    public function execute(array $payload): Branch
    {
        return DB::transaction(fn () => Branch::query()->create($payload));
    }
}
