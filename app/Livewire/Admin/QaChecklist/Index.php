<?php

namespace App\Livewire\Admin\QaChecklist;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('QA Checklist')]
class Index extends Component
{
    public function mount(): void
    {
        Gate::authorize('qa-checklist.view');
    }

    /**
     * A static, hand-curated record of what each admin module's List/Add/
     * Edit/View screens look like, captured in one QA sweep. Each screenshot
     * lives in public/images/qa-checklist/ — this is documentation, not
     * live data, so it's just a plain array rather than anything
     * database-backed.
     *
     * @return array<int, array{module: string, route: ?string, shots: array<int, array{label: string, file: string}>}>
     */
    protected function modules(): array
    {
        return [
            ['module' => 'Dashboard', 'route' => 'admin.dashboard', 'shots' => [
                ['label' => 'Overview', 'file' => 'dashboard-list.png'],
            ]],
            ['module' => 'Pages', 'route' => 'admin.pages.index', 'shots' => [
                ['label' => 'List', 'file' => 'pages-list.png'],
                ['label' => 'Edit', 'file' => 'pages-edit.png'],
            ]],
            ['module' => 'Settings', 'route' => 'admin.settings', 'shots' => [
                ['label' => 'Edit', 'file' => 'settings-edit.png'],
            ]],
            ['module' => 'Users', 'route' => 'admin.users.index', 'shots' => [
                ['label' => 'List', 'file' => 'users-list.png'],
                ['label' => 'Add', 'file' => 'users-add.png'],
                ['label' => 'Edit', 'file' => 'users-edit.png'],
            ]],
            ['module' => 'Roles', 'route' => 'admin.roles.index', 'shots' => [
                ['label' => 'List', 'file' => 'roles-list.png'],
                ['label' => 'Add', 'file' => 'roles-add.png'],
                ['label' => 'Edit', 'file' => 'roles-edit.png'],
            ]],
            ['module' => 'Permissions', 'route' => 'admin.permissions.index', 'shots' => [
                ['label' => 'List', 'file' => 'permissions-list.png'],
                ['label' => 'Add', 'file' => 'permissions-add.png'],
                ['label' => 'Edit', 'file' => 'permissions-edit.png'],
            ]],
            ['module' => 'Customers', 'route' => 'admin.customers.index', 'shots' => [
                ['label' => 'List', 'file' => 'customers-list.png'],
                ['label' => 'View', 'file' => 'customers-view.png'],
            ]],
            ['module' => 'Applications', 'route' => 'admin.applications.index', 'shots' => [
                ['label' => 'List', 'file' => 'applications-list.png'],
                ['label' => 'View', 'file' => 'applications-view.png'],
            ]],
            ['module' => 'Packages', 'route' => 'admin.packages.index', 'shots' => [
                ['label' => 'List', 'file' => 'packages-list.png'],
                ['label' => 'Add', 'file' => 'packages-add.png'],
                ['label' => 'Edit', 'file' => 'packages-edit.png'],
            ]],
            ['module' => 'Referral Management', 'route' => 'admin.referrals.index', 'shots' => [
                ['label' => 'List', 'file' => 'referrals-list.png'],
            ]],
            ['module' => 'Officers', 'route' => 'admin.officers.index', 'shots' => [
                ['label' => 'List', 'file' => 'officers-list.png'],
                ['label' => 'Add', 'file' => 'officers-add.png'],
                ['label' => 'Edit', 'file' => 'officers-edit.png'],
            ]],
            ['module' => 'Departments', 'route' => 'admin.departments.index', 'shots' => [
                ['label' => 'List', 'file' => 'departments-list.png'],
                ['label' => 'Add', 'file' => 'departments-add.png'],
                ['label' => 'Edit', 'file' => 'departments-edit.png'],
            ]],
            ['module' => 'Designations', 'route' => 'admin.designations.index', 'shots' => [
                ['label' => 'List', 'file' => 'designations-list.png'],
                ['label' => 'Add', 'file' => 'designations-add.png'],
                ['label' => 'Edit', 'file' => 'designations-edit.png'],
            ]],
            ['module' => 'Salary Profiles', 'route' => 'admin.salary-profiles.index', 'shots' => [
                ['label' => 'List', 'file' => 'salary-profiles-list.png'],
                ['label' => 'Add', 'file' => 'salary-profiles-add.png'],
                ['label' => 'Edit', 'file' => 'salary-profiles-edit.png'],
            ]],
            ['module' => 'Accounts Dashboard', 'route' => 'admin.accounts.index', 'shots' => [
                ['label' => 'Overview', 'file' => 'accounts-dashboard.png'],
            ]],
            ['module' => 'Payroll', 'route' => 'admin.payroll.index', 'shots' => [
                ['label' => 'List', 'file' => 'payroll-list.png'],
                ['label' => 'Generate', 'file' => 'payroll-generate.png'],
            ]],
            ['module' => 'Salary Payments', 'route' => 'admin.salary-payments.index', 'shots' => [
                ['label' => 'List', 'file' => 'salary-payments-list.png'],
                ['label' => 'Add', 'file' => 'salary-payments-add.png'],
            ]],
            ['module' => 'Expenses', 'route' => 'admin.expenses.index', 'shots' => [
                ['label' => 'List', 'file' => 'expenses-list.png'],
                ['label' => 'Add', 'file' => 'expenses-add.png'],
                ['label' => 'Edit', 'file' => 'expenses-edit.png'],
            ]],
            ['module' => 'Expense Categories', 'route' => 'admin.expense-categories.index', 'shots' => [
                ['label' => 'List', 'file' => 'expense-categories-list.png'],
                ['label' => 'Add', 'file' => 'expense-categories-add.png'],
                ['label' => 'Edit', 'file' => 'expense-categories-edit.png'],
            ]],
            ['module' => 'Financial Transactions', 'route' => 'admin.financial-transactions.index', 'shots' => [
                ['label' => 'List', 'file' => 'financial-transactions-list.png'],
                ['label' => 'Add', 'file' => 'financial-transactions-add.png'],
            ]],
            ['module' => 'Reports', 'route' => 'admin.reports.index', 'shots' => [
                ['label' => 'Overview', 'file' => 'reports-list.png'],
            ]],
            ['module' => 'Notifications', 'route' => 'admin.notifications.index', 'shots' => [
                ['label' => 'List', 'file' => 'notifications-list.png'],
            ]],
            ['module' => 'Activity Logs', 'route' => 'admin.activity-logs.index', 'shots' => [
                ['label' => 'List', 'file' => 'activity-logs-list.png'],
            ]],
        ];
    }

    public function render()
    {
        return view('livewire.admin.qa-checklist.index', [
            'modules' => $this->modules(),
            'capturedAt' => '26 Aug 2026',
        ]);
    }
}
