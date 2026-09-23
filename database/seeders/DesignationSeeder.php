<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * @var array<int, string>
     */
    protected array $designations = [
        'Marketing Officer',
        'Senior Marketing Officer',
        'Team Lead',
        'Area Manager',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->designations as $name) {
            Designation::firstOrCreate(['name' => $name]);
        }
    }
}
