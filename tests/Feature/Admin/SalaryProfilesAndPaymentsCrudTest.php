<?php

namespace Tests\Feature\Admin;

use App\Enums\OfficerStatus;
use App\Enums\SalaryProfileStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\SalaryPayments\Index as SalaryPaymentsIndex;
use App\Livewire\Admin\SalaryProfiles\Index as SalaryProfilesIndex;
use App\Models\Officer;
use App\Models\Role;
use App\Models\SalaryProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalaryProfilesAndPaymentsCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    protected function makeOfficer(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $user->roles()->attach($role);
        Officer::create(['user_id' => $user->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        return $user;
    }

    public function test_creating_a_new_active_salary_profile_deactivates_the_officers_previous_one(): void
    {
        $admin = $this->asSuperAdmin();
        $officer = $this->makeOfficer();

        $oldProfile = SalaryProfile::create([
            'officer_id' => $officer->id, 'basic_salary' => 20000,
            'effective_from' => now()->subYear(), 'status' => SalaryProfileStatus::Active,
        ]);

        Livewire::actingAs($admin)
            ->test(SalaryProfilesIndex::class)
            ->call('create')
            ->set('form.officer_id', (string) $officer->id)
            ->set('form.basic_salary', '30000')
            ->set('form.effective_from', now()->toDateString())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(SalaryProfileStatus::Inactive, $oldProfile->fresh()->status);
        $this->assertSame(1, SalaryProfile::where('officer_id', $officer->id)->where('status', SalaryProfileStatus::Active)->count());
    }

    public function test_salary_profile_requires_a_real_officer(): void
    {
        $admin = $this->asSuperAdmin();
        $randomUser = User::factory()->create(['status' => UserStatus::Active]); // not an officer

        Livewire::actingAs($admin)
            ->test(SalaryProfilesIndex::class)
            ->call('create')
            ->set('form.officer_id', (string) $randomUser->id)
            ->set('form.basic_salary', '30000')
            ->set('form.effective_from', now()->toDateString())
            ->call('save')
            ->assertHasErrors('form.officer_id');
    }

    public function test_a_salary_payment_can_be_recorded(): void
    {
        $admin = $this->asSuperAdmin();
        $officer = $this->makeOfficer();

        Livewire::actingAs($admin)
            ->test(SalaryPaymentsIndex::class)
            ->call('create')
            ->set('form.officer_id', (string) $officer->id)
            ->set('form.month', now()->format('Y-m'))
            ->set('form.amount', '28000')
            ->set('form.payment_date', now()->toDateString())
            ->set('form.payment_method', 'bank')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('salary_payments', ['officer_id' => $officer->id, 'amount' => 28000, 'paid_by' => $admin->id]);
    }

    public function test_salary_profiles_index_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.salary-profiles.index'))->assertForbidden();
    }

    public function test_payroll_index_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.payroll.index'))->assertForbidden();
    }
}
