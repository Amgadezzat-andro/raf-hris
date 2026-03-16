<?php

namespace App\Actions\HrCore;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class CreateDepartmentAction
{
    public function execute(array $payload): Department
    {
        return DB::transaction(fn () => Department::query()->create($payload));
    }
}
