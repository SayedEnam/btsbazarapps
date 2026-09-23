<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * @var array<int, string>
     */
    protected array $departments = [
        'Marketing',
        'Sales',
        'Customer Support',
        'Operations',
    ];

    /**
     * A couple of sub-departments under "Sales" as a working example of the
     * one-level nesting feature.
     *
     * @var array<int, string>
     */
    protected array $salesSubDepartments = [
        'Retail Sales',
        'Corporate Sales',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }

        $sales = Department::where('name', 'Sales')->whereNull('parent_id')->first();

        foreach ($this->salesSubDepartments as $name) {
            Department::firstOrCreate(['name' => $name], ['parent_id' => $sales?->id]);
        }
    }
}
