<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * @var array<int, string>
     */
    protected array $categories = [
        'Officer Salary',
        'Transport',
        'Office Expense',
        'Marketing Expense',
        'Advertisement',
        'Internet',
        'Mobile Bill',
        'Printing',
        'Other',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->categories as $name) {
            ExpenseCategory::firstOrCreate(['name' => $name]);
        }
    }
}
