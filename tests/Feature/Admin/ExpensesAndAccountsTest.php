<?php

namespace Tests\Feature\Admin;

use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\Accounts\Dashboard as AccountsDashboard;
use App\Livewire\Admin\ExpenseCategories\Index as ExpenseCategoriesIndex;
use App\Livewire\Admin\Expenses\Index as ExpensesIndex;
use App\Livewire\Admin\FinancialTransactions\Index as FinancialTransactionsIndex;
use App\Models\Application;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Package;
use App\Models\Role;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class ExpensesAndAccountsTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_an_expense_category_with_recorded_expenses_cannot_be_deleted(): void
    {
        $admin = $this->asSuperAdmin();
        $category = ExpenseCategory::create(['name' => 'Office Expense']);
        Expense::create([
            'category_id' => $category->id, 'amount' => 500, 'expense_date' => now(), 'created_by' => $admin->id,
        ]);

        Livewire::actingAs($admin)
            ->test(ExpenseCategoriesIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('delete');

        $this->assertDatabaseHas('expense_categories', ['id' => $category->id]);
    }

    public function test_an_unused_expense_category_can_be_deleted(): void
    {
        $admin = $this->asSuperAdmin();
        $category = ExpenseCategory::create(['name' => 'Office Expense']);

        Livewire::actingAs($admin)
            ->test(ExpenseCategoriesIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('delete');

        $this->assertDatabaseMissing('expense_categories', ['id' => $category->id]);
    }

    public function test_an_expense_can_be_recorded_and_attributed_to_the_creator(): void
    {
        $admin = $this->asSuperAdmin();
        $category = ExpenseCategory::create(['name' => 'Internet']);

        Livewire::actingAs($admin)
            ->test(ExpensesIndex::class)
            ->call('create')
            ->set('form.category_id', (string) $category->id)
            ->set('form.amount', '2000')
            ->set('form.expense_date', now()->toDateString())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('expenses', ['category_id' => $category->id, 'amount' => 2000, 'created_by' => $admin->id]);
    }

    public function test_the_expenses_filtered_total_reflects_every_matching_row_not_just_the_current_page(): void
    {
        $admin = $this->asSuperAdmin();
        $category = ExpenseCategory::create(['name' => 'Office Expense']);

        // 12 expenses so pagination (10/page) would otherwise hide 2 of them from a page-only sum.
        for ($i = 0; $i < 12; $i++) {
            Expense::create([
                'category_id' => $category->id, 'amount' => 100, 'expense_date' => now()->subDays($i), 'created_by' => $admin->id,
            ]);
        }

        Livewire::actingAs($admin)
            ->test(ExpensesIndex::class)
            ->assertSee('1,200.00');
    }

    /**
     * The expenses list shows each row's category name, so the list query
     * must eager-load `category` — otherwise every row fires its own query
     * (N+1). This asserts the query count stays flat as rows grow, which
     * would fail if the eager load were ever dropped again.
     */
    public function test_the_expenses_list_does_not_n_plus_one_on_category(): void
    {
        $admin = $this->asSuperAdmin();
        $categoryA = ExpenseCategory::create(['name' => 'Office Expense']);
        $categoryB = ExpenseCategory::create(['name' => 'Internet']);

        $countCategoryQueries = function (int $rowCount) use ($admin, $categoryA, $categoryB) {
            Expense::query()->delete();
            for ($i = 0; $i < $rowCount; $i++) {
                Expense::create([
                    'category_id' => $i % 2 === 0 ? $categoryA->id : $categoryB->id,
                    'amount' => 100, 'expense_date' => now()->subDays($i), 'created_by' => $admin->id,
                ]);
            }

            DB::enableQueryLog();
            Livewire::actingAs($admin)->test(ExpensesIndex::class);
            $count = collect(DB::getQueryLog())
                ->filter(fn ($q) => str_contains($q['query'], 'expense_categories'))
                ->count();
            DB::flushQueryLog();
            DB::disableQueryLog();

            return $count;
        };

        // If `category` weren't eager-loaded, the query count would grow with the row count (N+1).
        $this->assertSame($countCategoryQueries(2), $countCategoryQueries(8));
    }

    public function test_a_financial_transaction_can_be_recorded_manually(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(FinancialTransactionsIndex::class)
            ->call('create')
            ->set('form.transaction_type', 'income')
            ->set('form.amount', '5000')
            ->set('form.transaction_date', now()->toDateString())
            ->set('form.description', 'Miscellaneous income.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('financial_transactions', ['transaction_type' => 'income', 'amount' => 5000, 'created_by' => $admin->id]);
    }

    /**
     * The core financial-integrity rule from spec section 23: approving an
     * application must never fabricate an "income" ledger entry. This test
     * proves the Accounts dashboard total comes from the real Applications
     * table, and that no FinancialTransaction was silently created.
     */
    public function test_accounts_dashboard_computes_totals_from_real_records_and_never_synthesizes_income(): void
    {
        $admin = $this->asSuperAdmin();
        $customerRole = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $customerUser = User::factory()->create(['status' => UserStatus::Active]);
        $customerUser->roles()->attach($customerRole);
        $customer = Customer::create(['user_id' => $customerUser->id, 'status' => CustomerStatus::Active]);

        $package = Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);

        Application::create([
            'application_number' => 'APP-2026-000001', 'customer_id' => $customer->id, 'package_id' => $package->id,
            'package_price' => 1000, 'application_date' => now(), 'status' => ApplicationStatus::Approved,
        ]);

        SalaryPayment::create([
            'officer_id' => $admin->id, 'month' => now()->startOfMonth(), 'amount' => 300,
            'payment_date' => now(), 'payment_method' => 'cash', 'paid_by' => $admin->id,
        ]);

        $category = ExpenseCategory::create(['name' => 'Office Expense']);
        Expense::create(['category_id' => $category->id, 'amount' => 200, 'expense_date' => now(), 'created_by' => $admin->id]);

        Livewire::actingAs($admin)
            ->test(AccountsDashboard::class)
            ->assertSee('1,000.00') // Total Package Value
            ->assertSee('300.00')   // Total Salary Paid
            ->assertSee('200.00')   // Total Other Expense
            ->assertSee('500.00');  // Total Expense and Net Balance (both 500.00 here)

        // The critical assertion: no FinancialTransaction row exists anywhere,
        // proving nothing auto-created a fake "income" entry from the application.
        $this->assertSame(0, \App\Models\FinancialTransaction::count());
    }

    public function test_accounts_dashboard_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.accounts.index'))->assertForbidden();
    }
}
