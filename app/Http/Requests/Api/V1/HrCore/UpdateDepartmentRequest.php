<?php

namespace App\Http\Requests\Api\V1\HrCore;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $current = $this->route('department');

                    if ($value === null) {
                        return;
                    }

                    $parentId = (int) $value;

                    if ($parentId === (int) $current->id) {
                        $fail('The selected parent_id is invalid.');
                        return;
                    }

                    $walker = Department::query()->find($parentId);
                    while ($walker !== null) {
                        if ((int) $walker->id === (int) $current->id) {
                            $fail('The selected parent_id creates a cycle.');
                            return;
                        }

                        $walker = $walker->parent;
                    }
                },
            ],
            'name' => ['required', 'string', 'max:120'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ];
    }
}
