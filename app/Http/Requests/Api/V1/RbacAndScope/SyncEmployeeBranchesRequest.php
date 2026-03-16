<?php

namespace App\Http\Requests\Api\V1\RbacAndScope;

use Illuminate\Foundation\Http\FormRequest;

class SyncEmployeeBranchesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_ids' => ['required', 'array'],
            'branch_ids.*' => ['integer', 'distinct'],
        ];
    }
}
