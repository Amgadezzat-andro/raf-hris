<?php

namespace App\Http\Resources\Api\V1\Employees;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_code' => $this->employee_code,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'branch_id' => $this->branch_id,
            'department_id' => $this->department_id,
            'job_title_id' => $this->job_title_id,
            'status' => $this->status,
        ];
    }
}
