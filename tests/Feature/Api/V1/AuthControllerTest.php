<?php

namespace Tests\Feature\Api\V1;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_can_login_and_receive_token(): void
    {
        $employee = Employee::factory()->create([
            'email' => 'john@raf.local',
            'password' => 'password',
            'status' => 'active',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $employee->email,
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.employee.email', 'john@raf.local')
            ->assertJsonPath('data.token_type', 'Bearer');
    }

    public function test_inactive_employee_cannot_login(): void
    {
        $employee = Employee::factory()->create([
            'email' => 'inactive@raf.local',
            'password' => 'password',
            'status' => 'inactive',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $employee->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_authenticated_employee_can_fetch_profile(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->actingAs($employee, 'sanctum')->getJson('/api/v1/auth/me');

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $employee->id)
            ->assertJsonPath('data.email', $employee->email);
    }
}
