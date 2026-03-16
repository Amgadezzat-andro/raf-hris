<?php

namespace Tests\Feature\Api\V1;

use App\Models\Branch;
use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_list_employees(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.view');

        Employee::factory()->create([
            'full_name' => 'Target User',
            'status' => 'active',
        ]);

        $response = $this->actingAs($actor, 'sanctum')->getJson('/api/v1/employees');

        $response
            ->assertOk()
            ->assertJsonPath('meta.page', 1)
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.id', $actor->id);
    }

    public function test_authorized_employee_can_create_employee_with_generated_code(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.create');

        $branch = Branch::query()->create([
            'code' => 'CRO',
            'name' => 'Cairo Ops',
            'status' => 'active',
        ]);

        $response = $this->actingAs($actor, 'sanctum')->postJson('/api/v1/employees', [
            'full_name' => 'New Employee',
            'email' => 'new.employee@raf.local',
            'phone' => '01000000000',
            'password' => 'password123',
            'branch_id' => $branch->id,
            'department_id' => null,
            'job_title_id' => null,
            'hire_date' => '2026-03-01',
            'status' => 'active',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.email', 'new.employee@raf.local');

        $employeeCode = $response->json('data.employee_code');

        $this->assertMatchesRegularExpression('/^EMP-\d{6}$/', $employeeCode);
        $this->assertDatabaseHas('employees', [
            'email' => 'new.employee@raf.local',
            'employee_code' => $employeeCode,
        ]);
    }

    public function test_authorized_employee_can_view_and_update_employee(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('employees.view');
        $actor->givePermissionTo('employees.update');

        $target = Employee::factory()->create([
            'full_name' => 'Before Name',
            'status' => 'active',
        ]);

        $show = $this->actingAs($actor, 'sanctum')->getJson("/api/v1/employees/{$target->id}");

        $show
            ->assertOk()
            ->assertJsonPath('data.id', $target->id);

        $update = $this->actingAs($actor, 'sanctum')->putJson("/api/v1/employees/{$target->id}", [
            'full_name' => 'After Name',
            'email' => $target->email,
            'phone' => $target->phone,
            'password' => 'newpassword123',
            'branch_id' => $target->branch_id,
            'department_id' => $target->department_id,
            'job_title_id' => $target->job_title_id,
            'hire_date' => optional($target->hire_date)->toDateString(),
            'status' => 'inactive',
        ]);

        $update
            ->assertOk()
            ->assertJsonPath('data.full_name', 'After Name')
            ->assertJsonPath('data.status', 'inactive');
    }

    public function test_unauthorized_employee_cannot_list_employees(): void
    {
        $actor = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->getJson('/api/v1/employees');

        $response
            ->assertForbidden()
            ->assertJsonPath('message', 'You are not allowed to perform this action.');
    }
}
