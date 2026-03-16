<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            ['code' => 'HQ', 'name' => 'Headquarters', 'status' => 'active'],
            ['code' => 'CAI', 'name' => 'Cairo', 'status' => 'active'],
            ['code' => 'ALX', 'name' => 'Alexandria', 'status' => 'active'],
            ['code' => 'GIZ', 'name' => 'Giza', 'status' => 'active'],
            ['code' => 'MNS', 'name' => 'Mansoura', 'status' => 'inactive'],
        ];

        foreach ($branches as $payload) {
            Branch::query()->updateOrCreate(
                ['code' => $payload['code']],
                [
                    'name' => $payload['name'],
                    'status' => $payload['status'],
                ]
            );
        }
    }
}
