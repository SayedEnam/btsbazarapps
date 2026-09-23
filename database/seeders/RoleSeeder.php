<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::updateOrCreate(
            ['slug' => Role::SUPER_ADMIN],
            ['name' => 'Super Admin', 'description' => 'Full, unrestricted system access.', 'is_system' => true]
        );

        $admin = Role::updateOrCreate(
            ['slug' => Role::ADMIN],
            ['name' => 'Admin', 'description' => 'Manages assigned administrative and financial modules.', 'is_system' => true]
        );

        $officer = Role::updateOrCreate(
            ['slug' => Role::MARKETING_OFFICER],
            ['name' => 'Marketing Officer', 'description' => 'Manages own referrals and reviews referred applications.', 'is_system' => true]
        );

        $customer = Role::updateOrCreate(
            ['slug' => Role::CUSTOMER],
            ['name' => 'Customer', 'description' => 'Member of the platform holding a package application.', 'is_system' => true]
        );

        // Super Admin does not need explicit permissions: Gate::before in
        // AppServiceProvider grants it every ability unconditionally.

        $admin->permissions()->sync(
            Permission::whereIn('group', [
                'Users', 'Roles', 'Permissions', 'Officers', 'Departments', 'Designations', 'Customers', 'Referrals', 'Packages',
                'Applications', 'Salary Profiles', 'Payroll', 'Expense Categories', 'Expenses',
                'Financial Transactions', 'Accounts', 'Reports', 'Activity Logs', 'Pages', 'Settings',
            ])->pluck('id')
        );

        $officer->permissions()->sync(
            Permission::where(function ($query) {
                $query->where('slug', 'customers.view')
                    ->orWhere('slug', 'applications.view')
                    ->orWhere('slug', 'applications.approve')
                    ->orWhere('slug', 'applications.reject');
            })->pluck('id')
        );

        // Customers act on their own records only, through ownership checks
        // rather than the permission system, so no slugs are granted here.
        $customer->permissions()->sync([]);
    }
}
