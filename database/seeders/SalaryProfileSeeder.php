<?php

namespace Database\Seeders;

use App\Enums\SalaryProfileStatus;
use App\Models\SalaryProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalaryProfileSeeder extends Seeder
{
    /**
     * One active salary profile per Officer seeded in OfficerSeeder, with
     * basic_salary matching the figure already on their Officer record and
     * a modest set of allowances layered on top.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $profiles = [
        'nusrat.jahan@monthlybazar.test' => ['basic' => 35000, 'house' => 8000, 'transport' => 3000, 'mobile' => 1000],
        'shariful.islam@monthlybazar.test' => ['basic' => 28000, 'house' => 6000, 'transport' => 2500, 'mobile' => 1000],
        'rina.akter@monthlybazar.test' => ['basic' => 28000, 'house' => 6000, 'transport' => 2500, 'mobile' => 1000],
        'jahangir.alam@monthlybazar.test' => ['basic' => 42000, 'house' => 10000, 'transport' => 3500, 'mobile' => 1500],
        'salma.khatun@monthlybazar.test' => ['basic' => 27000, 'house' => 5500, 'transport' => 2000, 'mobile' => 1000],
        'rafiqul.islam@monthlybazar.test' => ['basic' => 27000, 'house' => 5500, 'transport' => 2000, 'mobile' => 1000],
        'taslima.nasrin@monthlybazar.test' => ['basic' => 45000, 'house' => 11000, 'transport' => 4000, 'mobile' => 1500],
        'mizanur.rahman@monthlybazar.test' => ['basic' => 28000, 'house' => 6000, 'transport' => 2500, 'mobile' => 1000],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->profiles as $email => $data) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                continue;
            }

            SalaryProfile::firstOrCreate(
                ['officer_id' => $user->id, 'status' => SalaryProfileStatus::Active],
                [
                    'basic_salary' => $data['basic'],
                    'house_allowance' => $data['house'],
                    'transport_allowance' => $data['transport'],
                    'mobile_allowance' => $data['mobile'],
                    'other_allowance' => 0,
                    'deduction' => 0,
                    'effective_from' => now()->subMonths(3)->startOfMonth(),
                ]
            );
        }
    }
}
