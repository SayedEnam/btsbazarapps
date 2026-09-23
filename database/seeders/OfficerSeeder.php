<?php

namespace Database\Seeders;

use App\Enums\OfficerStatus;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Officer;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    /**
     * Officer profile data for the Marketing Officer demo users created by
     * DemoUserSeeder (who already have a referral_code from CustomerSeeder).
     * Officer status is independent of the User account status — an officer
     * can be marked Inactive here while their login remains Active, or vice
     * versa; they're different concerns.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $profiles = [
        'nusrat.jahan@monthlybazar.test' => ['employee_id' => 'EMP-0001', 'department' => 'Marketing', 'designation' => 'Senior Marketing Officer', 'salary' => '35000.00', 'joined' => '2023-02-01', 'status' => OfficerStatus::Active],
        'shariful.islam@monthlybazar.test' => ['employee_id' => 'EMP-0002', 'department' => 'Marketing', 'designation' => 'Marketing Officer', 'salary' => '28000.00', 'joined' => '2023-05-15', 'status' => OfficerStatus::Active],
        'rina.akter@monthlybazar.test' => ['employee_id' => 'EMP-0003', 'department' => 'Sales', 'designation' => 'Marketing Officer', 'salary' => '28000.00', 'joined' => '2023-08-10', 'status' => OfficerStatus::Active],
        'jahangir.alam@monthlybazar.test' => ['employee_id' => 'EMP-0004', 'department' => 'Marketing', 'designation' => 'Team Lead', 'salary' => '42000.00', 'joined' => '2022-11-20', 'status' => OfficerStatus::Active],
        'salma.khatun@monthlybazar.test' => ['employee_id' => 'EMP-0005', 'department' => 'Customer Support', 'designation' => 'Marketing Officer', 'salary' => '27000.00', 'joined' => '2024-01-05', 'status' => OfficerStatus::Inactive],
        'rafiqul.islam@monthlybazar.test' => ['employee_id' => 'EMP-0006', 'department' => 'Sales', 'designation' => 'Marketing Officer', 'salary' => '27000.00', 'joined' => '2024-03-18', 'status' => OfficerStatus::Inactive],
        'taslima.nasrin@monthlybazar.test' => ['employee_id' => 'EMP-0007', 'department' => 'Operations', 'designation' => 'Area Manager', 'salary' => '45000.00', 'joined' => '2022-06-01', 'status' => OfficerStatus::Active],
        'mizanur.rahman@monthlybazar.test' => ['employee_id' => 'EMP-0008', 'department' => 'Marketing', 'designation' => 'Marketing Officer', 'salary' => '28000.00', 'joined' => '2024-06-22', 'status' => OfficerStatus::Active],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::pluck('id', 'name');
        $designations = Designation::pluck('id', 'name');

        foreach ($this->profiles as $email => $data) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                continue;
            }

            // updateOrCreate()'s lookup is scoped to non-trashed rows by
            // default, so if this demo officer was ever soft-deleted (e.g.
            // from the admin Officers screen, which only ever soft-deletes),
            // it would be invisible here and a fresh INSERT would collide
            // with the trashed row on the unique `user_id` constraint.
            // withTrashed() + an explicit restore keeps reseeding idempotent
            // regardless of that history.
            Officer::withTrashed()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => $data['employee_id'],
                    'department_id' => $departments->get($data['department']),
                    'designation_id' => $designations->get($data['designation']),
                    'joining_date' => $data['joined'],
                    'basic_salary' => $data['salary'],
                    'status' => $data['status'],
                    'deleted_at' => null,
                ]
            );
        }
    }
}
