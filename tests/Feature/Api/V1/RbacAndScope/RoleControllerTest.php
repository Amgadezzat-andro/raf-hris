<?php

namespace Tests\Feature\Api\V1\RbacAndScope;

use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleControllerTest extends TestCase
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

    public function test_authorized_employee_can_list_roles(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('roles.view');
        Role::findOrCreate('custom_role', 'web');

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/roles');

        $response
            ->assertOk()
            ->assertJsonPath('meta.page', 1);
    }

    public function test_unauthorized_employee_cannot_list_roles(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/roles');

        $response
            ->assertForbidden()
            ->assertJson([
                'message' => 'You are not allowed to perform this action.',
            ]);
    }

    public function test_authorized_employee_can_create_role(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('roles.create');

        $response = $this->actingAs($employee, 'sanctum')->postJson('/api/v1/roles', [
            'name' => 'payroll_reviewer',
            'permissions' => ['permissions.view'],
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'payroll_reviewer');
    }
}
