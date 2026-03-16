<?php

namespace Tests\Feature\Api\V1\RbacAndScope;

use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeScopeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_assign_roles_to_target_employee(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.assign_roles');
        $target = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->postJson("/api/v1/employees/{$target->id}/roles", [
            'roles' => ['employee'],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.employee_id', $target->id)
            ->assertJsonFragment(['employee']);
    }

    public function test_authorized_employee_can_sync_branch_scope(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.manage_scope');
        $target = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->putJson("/api/v1/employees/{$target->id}/branches/sync", [
            'branch_ids' => [1, 2],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.branch_ids.0', 1)
            ->assertJsonPath('data.branch_ids.1', 2);
    }

    public function test_authorized_employee_can_sync_department_scope(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.manage_scope');
        $target = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->putJson("/api/v1/employees/{$target->id}/departments/sync", [
            'department_ids' => [4, 7],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.department_ids.0', 4)
            ->assertJsonPath('data.department_ids.1', 7);
    }
}
