<?php

namespace App\Http\Requests\Api\V1\HrCore;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branchId = (int) $this->route('branch')->id;

        return [
            'code' => ['required', 'string', 'max:30', Rule::unique('branches', 'code')->ignore($branchId)],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }
}
