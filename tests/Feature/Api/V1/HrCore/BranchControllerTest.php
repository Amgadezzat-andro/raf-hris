<?php

namespace Tests\Feature\Api\V1\HrCore;

use App\Models\Branch;
use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_list_branches(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('branches.view');

        Branch::query()->create([
            'code' => 'HQ',
            'name' => 'Headquarters',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/branches');

        $response
            ->assertOk()
            ->assertJsonPath('meta.page', 1)
            ->assertJsonPath('data.0.code', 'HQ');
    }

    public function test_unauthorized_employee_cannot_list_branches(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/branches');

        $response
            ->assertForbidden()
            ->assertJson([
                'message' => 'You are not allowed to perform this action.',
            ]);
    }

    public function test_authorized_employee_can_create_branch(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('branches.create');

        $response = $this->actingAs($employee, 'sanctum')->postJson('/api/v1/branches', [
            'code' => 'ALEX',
            'name' => 'Alexandria',
            'status' => 'active',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.code', 'ALEX');

        $this->assertDatabaseHas('branches', [
            'code' => 'ALEX',
            'name' => 'Alexandria',
        ]);
    }

    public function test_authorized_employee_can_update_branch(): void
    {
        $employee = Employee::factory()->create();
        $employee->givePermissionTo('branches.update');

        $branch = Branch::query()->create([
            'code' => 'GZ',
            'name' => 'Giza',
            'status' => 'active',
        ]);

        $response = $this->actingAs($employee, 'sanctum')->putJson("/api/v1/branches/{$branch->id}", [
            'code' => 'GIZA',
            'name' => 'Giza Main',
            'status' => 'inactive',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.code', 'GIZA')
            ->assertJsonPath('data.status', 'inactive');

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'code' => 'GIZA',
            'status' => 'inactive',
        ]);
    }
}
