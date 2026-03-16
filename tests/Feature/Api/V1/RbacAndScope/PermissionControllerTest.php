<?php

namespace Tests\Feature\Api\V1\RbacAndScope;

use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionControllerTest extends TestCase
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

    public function test_authorized_employee_can_list_permissions(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('permissions.view');

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/permissions');

        $response
            ->assertOk()
            ->assertJsonPath('meta.page', 1)
            ->assertJsonStructure([
                'message',
                'data',
                'meta' => ['page', 'per_page', 'total', 'last_page'],
            ]);
    }
}
