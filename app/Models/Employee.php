<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    /** @use HasFactory<EmployeeFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'employee_code',
        'full_name',
        'email',
        'phone',
        'password',
        'branch_id',
        'department_id',
        'job_title_id',
        'hire_date',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'password' => 'hashed',
        ];
    }

    public function branches(): HasMany
    {
        return $this->hasMany(EmployeeBranch::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(EmployeeDepartment::class);
    }

    public function scopeVisibleTo(Builder $query, self $employee): Builder
    {
        if ($employee->hasRole('super_admin') || $employee->can('employees.manage_scope')) {
            return $query;
        }

        $branchIds = $employee->branches()->pluck('branch_id');
        $departmentIds = $employee->departments()->pluck('department_id');

        return $query->where(function (Builder $inner) use ($employee, $branchIds, $departmentIds): void {
            $inner->whereKey($employee->getKey())
                ->orWhereIn('branch_id', $branchIds)
                ->orWhereIn('department_id', $departmentIds);
        });
    }
}
