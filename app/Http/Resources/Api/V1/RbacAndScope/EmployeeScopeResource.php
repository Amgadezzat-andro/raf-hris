<?php

namespace App\Http\Resources\Api\V1\RbacAndScope;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeScopeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'employee_id' => $this->id,
            'branch_ids' => $this->branches->pluck('branch_id')->values(),
            'department_ids' => $this->departments->pluck('department_id')->values(),
        ];
    }
}
