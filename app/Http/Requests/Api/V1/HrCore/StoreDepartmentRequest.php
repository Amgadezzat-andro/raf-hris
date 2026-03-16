<?php

namespace App\Http\Requests\Api\V1\HrCore;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }
}
