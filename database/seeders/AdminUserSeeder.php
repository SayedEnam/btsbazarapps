<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@monthlybazar.test'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ]
        );

        $superAdmin = Role::where('slug', Role::SUPER_ADMIN)->firstOrFail();

        $user->roles()->syncWithoutDetaching([$superAdmin->id]);
    }
}
