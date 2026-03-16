<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class AdminEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employee = Employee::query()->firstOrCreate(
            ['email' => 'admin@raf.local'],
            [
                'employee_code' => 'EMP-000001',
                'full_name' => 'Super Admin',
                'password' => 'password',
                'status' => 'active',
            ]
        );

        $employee->syncRoles(['super_admin']);
    }
}
