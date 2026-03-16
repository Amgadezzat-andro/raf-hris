<?php

namespace App\Http\Resources\Api\V1\RbacAndScope;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeRoleAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'employee_id' => $this->id,
            'roles' => $this->getRoleNames()->values(),
            'permissions' => $this->getAllPermissions()->pluck('name')->values(),
        ];
    }
}
