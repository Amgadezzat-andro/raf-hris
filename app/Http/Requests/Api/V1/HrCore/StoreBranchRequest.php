<?php

namespace App\Http\Requests\Api\V1\HrCore;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30', 'unique:branches,code'],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }
}
