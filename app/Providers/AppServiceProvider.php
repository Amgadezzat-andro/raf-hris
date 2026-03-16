<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\JobTitle;
use App\Policies\EmployeePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\BranchPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\JobTitlePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Branch::class, BranchPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(JobTitle::class, JobTitlePolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
    }
}
