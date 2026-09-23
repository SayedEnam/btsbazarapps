<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            AdminUserSeeder::class,
            DemoUserSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            PackageSeeder::class,
            HeroSlideSeeder::class,
            TestimonialSeeder::class,
            CustomerSeeder::class,
            DepartmentSeeder::class,
            DesignationSeeder::class,
            OfficerSeeder::class,
            ApplicationSeeder::class,
            SalaryProfileSeeder::class,
            PayrollSeeder::class,
            ExpenseCategorySeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
