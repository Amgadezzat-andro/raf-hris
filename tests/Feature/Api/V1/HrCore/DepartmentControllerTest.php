<?php

namespace Tests\Feature\Api\V1\HrCore;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_list_departments_with_branch_filter(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('departments.view');

        $branchA = Branch::query()->create([
            'code' => 'CAI',
            'name' => 'Cairo',
            'status' => 'active',
        ]);

        $branchB = Branch::query()->create([
            'code' => 'MNS',
            'name' => 'Mansoura',
            'status' => 'active',
        ]);

        Department::query()->create([
            'branch_id' => $branchA->id,
            'parent_id' => null,
            'name' => 'Engineering',
            'status' => 'active',
        ]);

        Department::query()->create([
            'branch_id' => $branchB->id,
            'parent_id' => null,
            'name' => 'Finance',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson("/api/v1/departments?branch_id={$branchA->id}");

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.name', 'Engineering');
    }

    public function test_authorized_employee_can_create_department(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('departments.create');

        $branch = Branch::query()->create([
            'code' => 'ALX',
            'name' => 'Alex',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->postJson('/api/v1/departments', [
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Operations',
            'status' => 'active',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.branch_id', $branch->id)
            ->assertJsonPath('data.name', 'Operations');
    }

    public function test_authorized_employee_can_update_department(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('departments.update');

        $branch = Branch::query()->create([
            'code' => 'TAN',
            'name' => 'Tanta',
            'status' => 'active',
        ]);

        $department = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Sales',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->putJson("/api/v1/departments/{$department->id}", [
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Sales HQ',
            'status' => 'inactive',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Sales HQ')
            ->assertJsonPath('data.status', 'inactive');

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name' => 'Sales HQ',
            'status' => 'inactive',
        ]);
    }

    public function test_department_parent_cycle_is_rejected(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('departments.update');

        $branch = Branch::query()->create([
            'code' => 'ISM',
            'name' => 'Ismailia',
            'status' => 'active',
        ]);

        $root = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => null,
            'name' => 'Root',
            'status' => 'active',
        ]);

        $child = Department::query()->create([
            'branch_id' => $branch->id,
            'parent_id' => $root->id,
            'name' => 'Child',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->putJson("/api/v1/departments/{$root->id}", [
            'branch_id' => $branch->id,
            'parent_id' => $child->id,
            'name' => 'Root',
            'status' => 'active',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('message', 'The selected parent_id creates a cycle.');
    }
}
