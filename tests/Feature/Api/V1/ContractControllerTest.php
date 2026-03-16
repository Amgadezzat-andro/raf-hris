<?php

namespace Tests\Feature\Api\V1;

use App\Models\Contract;
use App\Models\Employee;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionSeeder::class,
        ]);
    }

    public function test_authorized_employee_can_list_contracts(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('contracts.view');

        $employee = Employee::factory()->create();
        Contract::query()->create([
            'employee_id' => $employee->id,
            'type' => 'full_time',
            'start_date' => '2026-01-01',
            'end_date' => null,
            'salary' => 15000,
            'currency' => 'EGP',
            'status' => 'active',
        ]);

        $response = $this->actingAs($actor, 'sanctum')->getJson('/api/v1/contracts');

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.type', 'full_time');
    }

    public function test_authorized_employee_can_create_contract_for_employee(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('contracts.create');

        $employee = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->postJson("/api/v1/employees/{$employee->id}/contracts", [
            'type' => 'part_time',
            'start_date' => '2026-03-01',
            'end_date' => '2026-12-31',
            'salary' => 9000,
            'currency' => 'EGP',
            'status' => 'draft',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.employee_id', $employee->id)
            ->assertJsonPath('data.type', 'part_time');
    }

    public function test_authorized_employee_can_view_and_update_contract(): void
    {
        $actor = Employee::factory()->create();
        $actor->givePermissionTo('contracts.view');
        $actor->givePermissionTo('contracts.update');

        $employee = Employee::factory()->create();
        $contract = Contract::query()->create([
            'employee_id' => $employee->id,
            'type' => 'full_time',
            'start_date' => '2026-02-01',
            'end_date' => null,
            'salary' => 12000,
            'currency' => 'EGP',
            'status' => 'active',
        ]);

        $show = $this->actingAs($actor, 'sanctum')->getJson("/api/v1/contracts/{$contract->id}");

        $show
            ->assertOk()
            ->assertJsonPath('data.id', $contract->id);

        $update = $this->actingAs($actor, 'sanctum')->putJson("/api/v1/contracts/{$contract->id}", [
            'type' => 'full_time',
            'start_date' => '2026-02-01',
            'end_date' => null,
            'salary' => 13000,
            'currency' => 'EGP',
            'status' => 'terminated',
        ]);

        $update
            ->assertOk()
            ->assertJsonPath('data.salary', '13000.00')
            ->assertJsonPath('data.status', 'terminated');
    }

    public function test_unauthorized_employee_cannot_list_contracts(): void
    {
        $actor = Employee::factory()->create();

        $response = $this->actingAs($actor, 'sanctum')->getJson('/api/v1/contracts');

        $response
            ->assertForbidden()
            ->assertJsonPath('message', 'You are not allowed to perform this action.');
    }
}
