<?php

namespace App\Http\Requests\Api\V1\HrCore;

use Illuminate\Foundation\Http\FormRequest;

class IndexJobTitleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'search' => ['nullable', 'string', 'max:120'],
        ];
    }
}
