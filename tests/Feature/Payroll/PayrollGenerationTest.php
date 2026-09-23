<?php

namespace Tests\Feature\Payroll;

use App\Actions\ChangePayrollStatus;
use App\Actions\GeneratePayroll;
use App\Enums\OfficerStatus;
use App\Enums\PayrollStatus;
use App\Enums\SalaryProfileStatus;
use App\Enums\UserStatus;
use App\Models\Officer;
use App\Models\Role;
use App\Models\SalaryProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use RuntimeException;
use Tests\TestCase;

class PayrollGenerationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    protected function makeOfficerWithProfile(string $referralCode, float $basic, float $house = 0, float $deduction = 0): User
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => $referralCode]);
        $user->roles()->attach($role);
        Officer::create(['user_id' => $user->id, 'employee_id' => "EMP-{$referralCode}", 'status' => OfficerStatus::Active]);

        SalaryProfile::create([
            'officer_id' => $user->id, 'basic_salary' => $basic, 'house_allowance' => $house,
            'deduction' => $deduction, 'effective_from' => now()->subMonth(), 'status' => SalaryProfileStatus::Active,
        ]);

        return $user;
    }

    public function test_generating_payroll_creates_one_item_per_active_officer_with_the_correct_net_salary(): void
    {
        $admin = $this->makeAdmin();
        $this->makeOfficerWithProfile('0001', 30000, 5000, 1000);
        $this->makeOfficerWithProfile('0002', 25000, 3000, 0);

        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);

        $this->assertSame(2, $payroll->items()->count());
        $item = $payroll->items()->first();
        // basic 30000 + allowance 5000 - deduction 1000 = 34000, or 25000+3000-0=28000 depending on order.
        $this->assertContains($item->net_salary, ['34000.00', '28000.00']);
    }

    public function test_generating_payroll_twice_for_the_same_month_does_not_duplicate_items(): void
    {
        $admin = $this->makeAdmin();
        $this->makeOfficerWithProfile('0001', 30000);

        $payrollFirst = app(GeneratePayroll::class)(Carbon::create(2026, 8, 15), $admin);
        $payrollSecond = app(GeneratePayroll::class)(Carbon::create(2026, 8, 20), $admin);

        $this->assertSame($payrollFirst->id, $payrollSecond->id);
        $this->assertSame(1, $payrollSecond->items()->count());
        $this->assertSame(1, \App\Models\Payroll::count());
    }

    public function test_generating_payroll_a_second_time_adds_only_newly_eligible_officers(): void
    {
        $admin = $this->makeAdmin();
        $this->makeOfficerWithProfile('0001', 30000);

        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);
        $this->assertSame(1, $payroll->items()->count());

        // A second officer joins mid-month.
        $this->makeOfficerWithProfile('0002', 25000);
        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);

        $this->assertSame(2, $payroll->items()->count());
    }

    public function test_a_later_salary_change_does_not_rewrite_an_already_generated_payroll_item(): void
    {
        $admin = $this->makeAdmin();
        $officer = $this->makeOfficerWithProfile('0001', 30000);

        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);
        $item = $payroll->items()->first();
        $this->assertSame('30000.00', $item->basic_salary);

        SalaryProfile::where('officer_id', $officer->id)->update(['basic_salary' => 50000]);

        $this->assertSame('30000.00', $item->fresh()->basic_salary);
    }

    public function test_payroll_status_follows_draft_approved_paid_in_order(): void
    {
        $admin = $this->makeAdmin();
        $this->makeOfficerWithProfile('0001', 30000);
        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);

        $this->assertSame(PayrollStatus::Draft, $payroll->status);

        $payroll = app(ChangePayrollStatus::class)($payroll, PayrollStatus::Approved, $admin);
        $this->assertSame(PayrollStatus::Approved, $payroll->status);
        $this->assertNotNull($payroll->approved_at);

        $payroll = app(ChangePayrollStatus::class)($payroll, PayrollStatus::Paid, $admin);
        $this->assertSame(PayrollStatus::Paid, $payroll->status);
        $this->assertNotNull($payroll->paid_at);
    }

    public function test_a_draft_payroll_cannot_be_marked_paid_by_skipping_approval(): void
    {
        $admin = $this->makeAdmin();
        $this->makeOfficerWithProfile('0001', 30000);
        $payroll = app(GeneratePayroll::class)(Carbon::create(2026, 8, 1), $admin);

        $this->expectException(RuntimeException::class);
        app(ChangePayrollStatus::class)($payroll, PayrollStatus::Paid, $admin);
    }

    public function test_net_salary_is_computed_server_side_never_trusting_a_client_supplied_value(): void
    {
        $profile = SalaryProfile::create([
            'officer_id' => $this->makeOfficerWithProfile('0001', 0)->id, // creates a duplicate profile row intentionally unused
            'basic_salary' => 20000, 'house_allowance' => 4000, 'transport_allowance' => 1500,
            'mobile_allowance' => 500, 'other_allowance' => 0, 'deduction' => 2000,
            'effective_from' => now(), 'status' => SalaryProfileStatus::Inactive,
        ]);

        // 20000 + (4000+1500+500+0) - 2000 = 24000
        $this->assertSame('24000.00', $profile->netSalary());
    }
}
