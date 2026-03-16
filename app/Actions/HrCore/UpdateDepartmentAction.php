<?php

namespace App\Actions\HrCore;

use App\Models\Department;
use Illuminate\Support\Facades\DB;

class UpdateDepartmentAction
{
    public function execute(Department $department, array $payload): Department
    {
        return DB::transaction(function () use ($department, $payload): Department {
            $department->update($payload);

            return $department->refresh();
        });
    }
}
