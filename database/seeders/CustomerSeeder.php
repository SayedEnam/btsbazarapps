<?php

namespace Database\Seeders;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Extra profile data for the Customer-role demo users created by
     * DemoUserSeeder, keyed by email. `referred_by` (an officer's email, or
     * null) demonstrates both paths: registered through a referral link,
     * and registered directly with no officer.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $profiles = [
        'sultana.parvin@monthlybazar.test' => ['father_name' => 'Abul Kalam', 'mother_name' => 'Rahima Begum', 'nid' => '1990123456001', 'profession' => 'Teacher', 'gender' => 'female', 'status' => CustomerStatus::Active, 'referred_by' => 'nusrat.jahan@monthlybazar.test'],
        'habibur.rahman@monthlybazar.test' => ['father_name' => 'Aziz Mia', 'mother_name' => 'Halima Khatun', 'nid' => '1988123456002', 'profession' => 'Businessman', 'gender' => 'male', 'status' => CustomerStatus::Active, 'referred_by' => 'nusrat.jahan@monthlybazar.test'],
        'ayesha.siddika@monthlybazar.test' => ['father_name' => 'Mokbul Hossain', 'mother_name' => 'Jesmin Akter', 'nid' => '1995123456003', 'profession' => 'Student', 'gender' => 'female', 'status' => CustomerStatus::Pending, 'referred_by' => 'shariful.islam@monthlybazar.test'],
        'nazrul.islam@monthlybazar.test' => ['father_name' => 'Sirajul Islam', 'mother_name' => 'Rokeya Begum', 'nid' => '1985123456004', 'profession' => 'Farmer', 'gender' => 'male', 'status' => CustomerStatus::Active, 'referred_by' => null],
        'ruma.aktar@monthlybazar.test' => ['father_name' => 'Nurul Amin', 'mother_name' => 'Sufia Khatun', 'nid' => '1992123456005', 'profession' => 'Tailor', 'gender' => 'female', 'status' => CustomerStatus::Pending, 'referred_by' => 'rina.akter@monthlybazar.test'],
        'shahidul.islam@monthlybazar.test' => ['father_name' => 'Jamal Uddin', 'mother_name' => 'Amena Khatun', 'nid' => '1980123456006', 'profession' => 'Driver', 'gender' => 'male', 'status' => CustomerStatus::Suspended, 'referred_by' => null],
        'moushumi.rahman@monthlybazar.test' => ['father_name' => 'Golam Rabbani', 'mother_name' => 'Nasima Begum', 'nid' => '1993123456007', 'profession' => 'Nurse', 'gender' => 'female', 'status' => CustomerStatus::Active, 'referred_by' => 'jahangir.alam@monthlybazar.test'],
        'delwar.hossain@monthlybazar.test' => ['father_name' => 'Fazlur Rahman', 'mother_name' => 'Shirin Akter', 'nid' => '1987123456008', 'profession' => 'Shop Owner', 'gender' => 'male', 'status' => CustomerStatus::Inactive, 'referred_by' => null],
        'farida.yasmin@monthlybazar.test' => ['father_name' => 'Habibur Rahman', 'mother_name' => 'Rashida Begum', 'nid' => '1991123456009', 'profession' => 'Homemaker', 'gender' => 'female', 'status' => CustomerStatus::Active, 'referred_by' => 'taslima.nasrin@monthlybazar.test'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officerRole = Role::where('slug', Role::MARKETING_OFFICER)->firstOrFail();

        // Assign a referral code to every Marketing Officer demo user that
        // doesn't already have one, in a stable (name-ordered) sequence so
        // reseeding never reshuffles who holds which code.
        $officers = $officerRole->users()->whereNull('referral_code')->orderBy('name')->get();

        foreach ($officers as $officer) {
            $officer->update(['referral_code' => User::generateReferralCode()]);
        }

        foreach ($this->profiles as $email => $data) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                continue;
            }

            // withTrashed() so re-seeding stays idempotent even if this demo
            // customer was ever soft-deleted — updateOrCreate()'s default
            // lookup excludes trashed rows, which would otherwise attempt a
            // fresh INSERT that collides with the trashed row's unique
            // `user_id` constraint (see the identical fix in OfficerSeeder).
            $customer = Customer::withTrashed()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'father_name' => $data['father_name'],
                    'mother_name' => $data['mother_name'],
                    'nid_number' => $data['nid'],
                    'profession' => $data['profession'],
                    'gender' => $data['gender'],
                    'status' => $data['status'],
                    'deleted_at' => null,
                ]
            );

            if ($data['referred_by']) {
                $officer = User::where('email', $data['referred_by'])->first();

                if ($officer && $officer->referral_code) {
                    Referral::updateOrCreate(
                        ['customer_id' => $customer->id],
                        [
                            'officer_id' => $officer->id,
                            'referral_code' => $officer->referral_code,
                            'registered_at' => $customer->created_at ?? now(),
                        ]
                    );
                }
            }
        }
    }
}
