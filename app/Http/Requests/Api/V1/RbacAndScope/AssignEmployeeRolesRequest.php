<?php

namespace App\Http\Requests\Api\V1\RbacAndScope;

use Illuminate\Foundation\Http\FormRequest;

class AssignEmployeeRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ];
    }
}
