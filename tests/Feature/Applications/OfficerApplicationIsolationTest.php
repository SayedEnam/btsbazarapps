<?php

namespace Tests\Feature\Applications;

use App\Actions\SubmitApplication;
use App\Enums\CustomerStatus;
use App\Enums\OfficerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Officer\Applications\Index as OfficerApplicationsIndex;
use App\Models\Customer;
use App\Models\Officer;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OfficerApplicationIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOfficer(string $referralCode): User
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);

        // Mirrors what RoleSeeder grants the Marketing Officer role in
        // production — without this, Gate::authorize('applications.approve')
        // denies everyone regardless of the officer_id scoping being tested.
        foreach (['applications.view', 'applications.approve', 'applications.reject'] as $slug) {
            $permission = Permission::firstOrCreate(['slug' => $slug], ['name' => $slug, 'group' => 'Applications']);
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        }

        $user = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => $referralCode]);
        $user->roles()->attach($role);
        Officer::create(['user_id' => $user->id, 'employee_id' => "EMP-{$referralCode}", 'status' => OfficerStatus::Active]);

        return $user;
    }

    protected function makeReferredApplication(User $officer): \App\Models\Application
    {
        $customerRole = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $customerUser = User::factory()->create(['status' => UserStatus::Active]);
        $customerUser->roles()->attach($customerRole);
        $customer = Customer::create(['user_id' => $customerUser->id, 'status' => CustomerStatus::Pending]);

        Referral::create([
            'officer_id' => $officer->id, 'customer_id' => $customer->id,
            'referral_code' => $officer->referral_code, 'registered_at' => now(),
        ]);

        $package = Package::firstOrCreate(
            ['code' => 'STD-1000'],
            ['name' => 'Standard Membership', 'slug' => 'standard-membership', 'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1]
        );

        return app(SubmitApplication::class)($customer, $package);
    }

    public function test_an_officer_cannot_approve_an_application_assigned_to_another_officer(): void
    {
        $officerA = $this->makeOfficer('0001');
        $officerB = $this->makeOfficer('0002');

        $applicationForB = $this->makeReferredApplication($officerB);

        // ownApplicationOrFail() scopes the lookup to Auth::id(), so Officer A
        // attempting to act on Officer B's application 404s rather than
        // silently no-op-ing — there's no cross-officer application to find.
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::actingAs($officerA)
            ->test(OfficerApplicationsIndex::class)
            ->call('approve', $applicationForB->id);
    }

    public function test_an_officer_can_approve_their_own_assigned_application(): void
    {
        $officer = $this->makeOfficer('0001');
        $application = $this->makeReferredApplication($officer);

        Livewire::actingAs($officer)
            ->test(OfficerApplicationsIndex::class)
            ->call('approve', $application->id);

        $this->assertSame('approved', $application->fresh()->status->value);
    }

    public function test_officer_applications_list_only_shows_their_own_assigned_applications(): void
    {
        $officerA = $this->makeOfficer('0001');
        $officerB = $this->makeOfficer('0002');

        $applicationForA = $this->makeReferredApplication($officerA);
        $applicationForB = $this->makeReferredApplication($officerB);

        Livewire::actingAs($officerA)
            ->test(OfficerApplicationsIndex::class)
            ->assertSee($applicationForA->application_number)
            ->assertDontSee($applicationForB->application_number);
    }
}
