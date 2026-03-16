<?php

namespace App\Http\Requests\Api\V1\RbacAndScope;

use Illuminate\Foundation\Http\FormRequest;

class SyncEmployeeDepartmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_ids' => ['required', 'array'],
            'department_ids.*' => ['integer', 'distinct'],
        ];
    }
}
