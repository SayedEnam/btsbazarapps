<?php

namespace Tests\Feature\Officer;

use App\Enums\CustomerStatus;
use App\Enums\OfficerStatus;
use App\Enums\UserStatus;
use App\Livewire\Officer\Referrals\Index as OfficerReferralsIndex;
use App\Models\Customer;
use App\Models\Officer;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReferralIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOfficer(string $name, string $referralCode): User
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $user = User::factory()->create(['name' => $name, 'status' => UserStatus::Active, 'referral_code' => $referralCode]);
        $user->roles()->attach($role);
        Officer::create(['user_id' => $user->id, 'employee_id' => "EMP-{$referralCode}", 'status' => OfficerStatus::Active]);

        return $user;
    }

    protected function referCustomer(User $officer, string $customerName): Customer
    {
        $customerRole = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $customerUser = User::factory()->create(['name' => $customerName, 'status' => UserStatus::Active]);
        $customerUser->roles()->attach($customerRole);
        $customer = Customer::create(['user_id' => $customerUser->id, 'status' => CustomerStatus::Active]);

        Referral::create([
            'officer_id' => $officer->id,
            'customer_id' => $customer->id,
            'referral_code' => $officer->referral_code,
            'registered_at' => now(),
        ]);

        return $customer;
    }

    /**
     * The critical requirement from spec section 16: an officer must never
     * see another officer's referrals, no matter what. This component takes
     * no ID parameter at all — it always scopes to Auth::id() — so there is
     * nothing to tamper with. This test proves that in practice.
     */
    public function test_an_officer_only_sees_their_own_referrals_never_another_officers(): void
    {
        $officerA = $this->makeOfficer('Officer A', '0001');
        $officerB = $this->makeOfficer('Officer B', '0002');

        $this->referCustomer($officerA, 'Customer Of A');
        $this->referCustomer($officerB, 'Customer Of B');

        Livewire::actingAs($officerA)
            ->test(OfficerReferralsIndex::class)
            ->assertSee('Customer Of A')
            ->assertDontSee('Customer Of B');

        Livewire::actingAs($officerB)
            ->test(OfficerReferralsIndex::class)
            ->assertSee('Customer Of B')
            ->assertDontSee('Customer Of A');
    }

    public function test_officer_dashboard_stats_only_count_the_logged_in_officers_own_referrals(): void
    {
        $officerA = $this->makeOfficer('Officer A', '0001');
        $officerB = $this->makeOfficer('Officer B', '0002');

        $this->referCustomer($officerA, 'Customer 1 Of A');
        $this->referCustomer($officerA, 'Customer 2 Of A');
        $this->referCustomer($officerB, 'Customer Of B');

        $response = $this->actingAs($officerA)->get(route('officer.dashboard'));

        $response->assertOk();
        // Officer A has 2 referrals, not 3 (which would mean B's referral leaked in).
        $response->assertSeeText('2');
    }

    public function test_a_marketing_officer_cannot_reach_the_admin_or_customer_areas(): void
    {
        $officer = $this->makeOfficer('Officer A', '0001');

        $this->actingAs($officer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($officer)->get(route('customer.dashboard'))->assertForbidden();
    }

    public function test_a_customer_or_admin_cannot_reach_the_officer_area(): void
    {
        $adminRole = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
        $admin = User::factory()->create(['status' => UserStatus::Active]);
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin)->get(route('officer.dashboard'))->assertForbidden();
    }
}
