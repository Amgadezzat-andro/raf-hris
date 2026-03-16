<?php

namespace Tests\Feature\Api\V1\HrCore;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobTitle;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTitleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_list_job_titles_with_department_filter(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('job_titles.view');

        $branch = Branch::query()->create([
            'code' => 'BEN',
            'name' => 'Beni Suef',
            'status' => 'active',
        ]);

        $departmentA = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'IT',
            'status' => 'active',
        ]);

        $departmentB = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'HR',
            'status' => 'active',
        ]);

        JobTitle::query()->create([
            'department_id' => $departmentA->id,
            'name' => 'Backend Engineer',
            'status' => 'active',
        ]);

        JobTitle::query()->create([
            'department_id' => $departmentB->id,
            'name' => 'Recruiter',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/v1/job-titles?department_id={$departmentA->id}");

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Backend Engineer');
    }

    public function test_authorized_employee_can_create_job_title(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('job_titles.create');

        $branch = Branch::query()->create([
            'code' => 'SUE',
            'name' => 'Suez',
            'status' => 'active',
        ]);

        $department = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Engineering',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->postJson('/api/v1/job-titles', [
            'department_id' => $department->id,
            'name' => 'DevOps Engineer',
            'status' => 'active',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.department_id', $department->id)
            ->assertJsonPath('data.name', 'DevOps Engineer');
    }

    public function test_authorized_employee_can_update_job_title(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('job_titles.update');

        $branch = Branch::query()->create([
            'code' => 'FYM',
            'name' => 'Fayoum',
            'status' => 'active',
        ]);

        $department = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Finance',
            'status' => 'active',
        ]);

        $jobTitle = JobTitle::query()->create([
            'department_id' => $department->id,
            'name' => 'Analyst',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->putJson("/api/v1/job-titles/{$jobTitle->id}", [
            'department_id' => $department->id,
            'name' => 'Senior Analyst',
            'status' => 'inactive',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Senior Analyst')
            ->assertJsonPath('data.status', 'inactive');

        $this->assertDatabaseHas('job_titles', [
            'id' => $jobTitle->id,
            'name' => 'Senior Analyst',
            'status' => 'inactive',
        ]);
    }
}
