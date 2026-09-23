<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $expenses = [
        ['category' => 'Office Expense', 'amount' => 4500, 'paid_by' => 'Dhaka Stationery House', 'daysAgo' => 5, 'description' => 'Office supplies for the month.'],
        ['category' => 'Internet', 'amount' => 2000, 'paid_by' => 'Link3 Technologies', 'daysAgo' => 10, 'description' => 'Monthly office internet bill.'],
        ['category' => 'Mobile Bill', 'amount' => 1500, 'paid_by' => 'Grameenphone', 'daysAgo' => 10, 'description' => 'Office mobile connections.'],
        ['category' => 'Transport', 'amount' => 3200, 'paid_by' => null, 'daysAgo' => 15, 'description' => 'Field visit transport costs.'],
        ['category' => 'Marketing Expense', 'amount' => 8000, 'paid_by' => 'Print Media BD', 'daysAgo' => 20, 'description' => 'Local leaflet printing and distribution.'],
        ['category' => 'Advertisement', 'amount' => 12000, 'paid_by' => 'Facebook Ads', 'daysAgo' => 25, 'description' => 'Social media ad campaign.'],
        ['category' => 'Printing', 'amount' => 1800, 'paid_by' => 'Quick Print', 'daysAgo' => 8, 'description' => 'Membership forms and brochures.'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@monthlybazar.test')->first();

        if (! $admin || Expense::exists()) {
            return;
        }

        foreach ($this->expenses as $data) {
            $category = ExpenseCategory::where('name', $data['category'])->first();

            if (! $category) {
                continue;
            }

            Expense::create([
                'category_id' => $category->id,
                'amount' => $data['amount'],
                'expense_date' => now()->subDays($data['daysAgo'])->toDateString(),
                'description' => $data['description'],
                'paid_by' => $data['paid_by'],
                'created_by' => $admin->id,
            ]);
        }
    }
}
