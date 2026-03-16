<?php

namespace App\Actions\HrCore;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;

class UpdateBranchAction
{
    public function execute(Branch $branch, array $payload): Branch
    {
        return DB::transaction(function () use ($branch, $payload): Branch {
            $branch->update($payload);

            return $branch->refresh();
        });
    }
}
