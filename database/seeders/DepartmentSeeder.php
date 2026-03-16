<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::query()->pluck('id', 'code');

        $hq = $this->upsertDepartment((int) $branches['HQ'], null, 'Head Office', 'active');
        $ops = $this->upsertDepartment((int) $branches['HQ'], $hq->id, 'Operations', 'active');
        $this->upsertDepartment((int) $branches['HQ'], $ops->id, 'Logistics', 'active');
        $this->upsertDepartment((int) $branches['HQ'], $ops->id, 'Facilities', 'active');

        $this->upsertDepartment((int) $branches['CAI'], null, 'Engineering', 'active');
        $this->upsertDepartment((int) $branches['CAI'], null, 'Finance', 'active');
        $this->upsertDepartment((int) $branches['CAI'], null, 'Human Resources', 'active');

        $this->upsertDepartment((int) $branches['ALX'], null, 'Customer Support', 'active');
        $this->upsertDepartment((int) $branches['ALX'], null, 'Sales', 'active');

        $this->upsertDepartment((int) $branches['GIZ'], null, 'Procurement', 'active');
        $this->upsertDepartment((int) $branches['MNS'], null, 'Field Services', 'inactive');
    }

    private function upsertDepartment(int $branchId, ?int $parentId, string $name, string $status): Department
    {
        return Department::query()->updateOrCreate(
            [
                'branch_id' => $branchId,
                'parent_id' => $parentId,
                'name' => $name,
            ],
            ['status' => $status]
        );
    }
}
