<?php

namespace App\Http\Requests\Api\V1\Contracts;

use Illuminate\Foundation\Http\FormRequest;

class IndexContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'status' => ['nullable', 'string', 'in:draft,active,expired,terminated'],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
        ];
    }
}
