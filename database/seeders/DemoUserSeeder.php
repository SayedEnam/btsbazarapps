<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoUserSeeder extends Seeder
{
    /**
     * Demo accounts for exercising search, filters, and pagination on the
     * Users screen. Mixes Admins, Marketing Officers, and Customers across
     * all three statuses. Officer/Customer *profile* records (employee id,
     * referral code, NID, etc.) belong to their own modules in later phases
     * — these are plain user accounts only.
     *
     * @var array<int, array{name: string, email: string, phone: string, role: string, status: UserStatus}>
     */
    protected array $demoUsers = [
        ['name' => 'Md. Karim Hossain', 'email' => 'karim.hossain@monthlybazar.test', 'phone' => '01711000001', 'role' => Role::ADMIN, 'status' => UserStatus::Active],
        ['name' => 'Fatema Begum', 'email' => 'fatema.begum@monthlybazar.test', 'phone' => '01711000002', 'role' => Role::ADMIN, 'status' => UserStatus::Active],
        ['name' => 'Abdur Rahman', 'email' => 'abdur.rahman@monthlybazar.test', 'phone' => '01711000003', 'role' => Role::ADMIN, 'status' => UserStatus::Inactive],

        ['name' => 'Nusrat Jahan', 'email' => 'nusrat.jahan@monthlybazar.test', 'phone' => '01711000004', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],
        ['name' => 'Shariful Islam', 'email' => 'shariful.islam@monthlybazar.test', 'phone' => '01711000005', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],
        ['name' => 'Rina Akter', 'email' => 'rina.akter@monthlybazar.test', 'phone' => '01711000006', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],
        ['name' => 'Jahangir Alam', 'email' => 'jahangir.alam@monthlybazar.test', 'phone' => '01711000007', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],
        ['name' => 'Salma Khatun', 'email' => 'salma.khatun@monthlybazar.test', 'phone' => '01711000008', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Suspended],
        ['name' => 'Rafiqul Islam', 'email' => 'rafiqul.islam@monthlybazar.test', 'phone' => '01711000009', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Inactive],
        ['name' => 'Taslima Nasrin', 'email' => 'taslima.nasrin@monthlybazar.test', 'phone' => '01711000010', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],
        ['name' => 'Mizanur Rahman', 'email' => 'mizanur.rahman@monthlybazar.test', 'phone' => '01711000011', 'role' => Role::MARKETING_OFFICER, 'status' => UserStatus::Active],

        ['name' => 'Sultana Parvin', 'email' => 'sultana.parvin@monthlybazar.test', 'phone' => '01711000012', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
        ['name' => 'Habibur Rahman', 'email' => 'habibur.rahman@monthlybazar.test', 'phone' => '01711000013', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
        ['name' => 'Ayesha Siddika', 'email' => 'ayesha.siddika@monthlybazar.test', 'phone' => '01711000014', 'role' => Role::CUSTOMER, 'status' => UserStatus::Inactive],
        ['name' => 'Nazrul Islam', 'email' => 'nazrul.islam@monthlybazar.test', 'phone' => '01711000015', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
        ['name' => 'Ruma Aktar', 'email' => 'ruma.aktar@monthlybazar.test', 'phone' => '01711000016', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
        ['name' => 'Shahidul Islam', 'email' => 'shahidul.islam@monthlybazar.test', 'phone' => '01711000017', 'role' => Role::CUSTOMER, 'status' => UserStatus::Suspended],
        ['name' => 'Moushumi Rahman', 'email' => 'moushumi.rahman@monthlybazar.test', 'phone' => '01711000018', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
        ['name' => 'Delwar Hossain', 'email' => 'delwar.hossain@monthlybazar.test', 'phone' => '01711000019', 'role' => Role::CUSTOMER, 'status' => UserStatus::Inactive],
        ['name' => 'Farida Yasmin', 'email' => 'farida.yasmin@monthlybazar.test', 'phone' => '01711000020', 'role' => Role::CUSTOMER, 'status' => UserStatus::Active],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::whereIn('slug', [Role::ADMIN, Role::MARKETING_OFFICER, Role::CUSTOMER])
            ->get()
            ->keyBy('slug');

        foreach ($this->demoUsers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'username' => Str::before($data['email'], '@'),
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'status' => $data['status'],
                    'email_verified_at' => now(),
                ]
            );

            $role = $roles->get($data['role']);

            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
