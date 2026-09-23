<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * The full permission catalogue for the application.
     *
     * Only the modules built so far (users, roles, permissions) are actually
     * enforced today; the rest are seeded up front so every future module
     * (officers, customers, packages, applications, payroll, expenses,
     * reports, settings) can be wired to roles from day one without another
     * migration.
     *
     * @var array<string, array<int, string>>
     */
    protected array $catalogue = [
        'Users' => ['view', 'create', 'edit', 'delete'],
        'Roles' => ['view', 'create', 'edit', 'delete'],
        'Permissions' => ['view', 'create', 'edit', 'delete'],
        'Officers' => ['view', 'create', 'edit', 'delete'],
        'Departments' => ['view', 'create', 'edit', 'delete'],
        'Designations' => ['view', 'create', 'edit', 'delete'],
        'Customers' => ['view', 'create', 'edit', 'delete'],
        'Referrals' => ['view'],
        'Packages' => ['view', 'create', 'edit', 'delete'],
        'Applications' => ['view', 'approve', 'reject', 'cancel', 'reassign'],
        'Salary Profiles' => ['view', 'create', 'edit', 'delete'],
        'Payroll' => ['view', 'create', 'edit', 'approve', 'pay'],
        'Expense Categories' => ['view', 'create', 'edit', 'delete'],
        'Expenses' => ['view', 'create', 'edit', 'delete'],
        'Financial Transactions' => ['view', 'create', 'edit', 'delete'],
        'Accounts' => ['view'],
        'Reports' => ['view', 'export'],
        'Activity Logs' => ['view'],
        'Pages' => ['view', 'edit'],
        'Settings' => ['view', 'edit'],
        'QA Checklist' => ['view'],
        'Hero Slides' => ['view', 'create', 'edit', 'delete'],
        'Testimonials' => ['view', 'create', 'edit', 'delete'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->catalogue as $group => $actions) {
            // Str::slug (not strtolower) so a multi-word group like "Salary
            // Profiles" produces "salary-profiles.view", not a slug with a
            // literal space in it that would break the dotted-slug
            // permission convention Gate::before relies on.
            $prefix = Str::slug($group);

            foreach ($actions as $action) {
                Permission::updateOrCreate(
                    ['slug' => "{$prefix}.{$action}"],
                    [
                        'name' => ucfirst($action).' '.$group,
                        'group' => $group,
                        'description' => "Allows the user to {$action} {$prefix}.",
                    ]
                );
            }
        }
    }
}
