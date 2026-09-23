<?php

namespace Tests\Feature\Admin;

use App\Actions\SubmitApplication;
use App\Enums\CustomerStatus;
use App\Enums\OfficerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\Customers\Index as CustomersIndex;
use App\Livewire\Admin\Referrals\Index as ReferralsIndex;
use App\Models\Customer;
use App\Models\Officer;
use App\Models\Package;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomersAndReferralsTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    protected function makeCustomer(string $name = 'Jane Customer'): Customer
    {
        $role = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $user = User::factory()->create(['name' => $name, 'status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Active]);
    }

    protected function makeOfficer(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $user->roles()->attach($role);
        Officer::create(['user_id' => $user->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        return $user;
    }

    protected function makePackage(): Package
    {
        return Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
    }

    public function test_customers_index_requires_permission(): void
    {
        $role = Role::firstOrCreate(['slug' => Role::ADMIN], ['name' => 'Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.customers.index'))->assertForbidden();
    }

    public function test_customers_index_lists_and_searches_by_name(): void
    {
        $admin = $this->asSuperAdmin();
        $this->makeCustomer('Jane Customer');
        $this->makeCustomer('Someone Else');

        Livewire::actingAs($admin)
            ->test(CustomersIndex::class)
            ->assertSee('Jane Customer')
            ->assertSee('Someone Else')
            ->set('search', 'Jane')
            ->assertSee('Jane Customer')
            ->assertDontSee('Someone Else');
    }

    public function test_customer_detail_modal_shows_applications_and_referral_officer(): void
    {
        $admin = $this->asSuperAdmin();
        $officer = $this->makeOfficer();
        $customer = $this->makeCustomer('Jane Customer');
        $package = $this->makePackage();
        Referral::create([
            'officer_id' => $officer->id, 'customer_id' => $customer->id,
            'referral_code' => $officer->referral_code, 'registered_at' => now(),
        ]);
        $application = app(SubmitApplication::class)($customer, $package);

        Livewire::actingAs($admin)
            ->test(CustomersIndex::class)
            ->call('view', $customer->id)
            ->assertSee($officer->name)
            ->assertSee($application->application_number);
    }

    public function test_admin_can_manually_change_a_customers_membership_status(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();

        Livewire::actingAs($admin)
            ->test(CustomersIndex::class)
            ->call('view', $customer->id)
            ->call('updateStatus', $customer->id, 'suspended')
            ->assertHasNoErrors();

        $this->assertSame(CustomerStatus::Suspended, $customer->fresh()->status);
        $this->assertDatabaseHas('activity_logs', [
            'module' => 'Customers',
            'action' => 'status_changed',
        ]);
    }

    public function test_customers_edit_permission_is_required_to_change_status(): void
    {
        $role = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $permission = \App\Models\Permission::firstOrCreate(
            ['slug' => 'customers.view'],
            ['name' => 'View Customers', 'group' => 'Customers']
        );
        $role->permissions()->syncWithoutDetaching([$permission->id]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $customer = $this->makeCustomer();

        // Livewire absorbs the AuthorizationException thrown inside the
        // action rather than re-throwing it to the test, so the real proof
        // the gate held is that the write never happened.
        Livewire::actingAs($user)
            ->test(CustomersIndex::class)
            ->call('updateStatus', $customer->id, 'suspended');

        $this->assertSame(CustomerStatus::Active, $customer->fresh()->status);
    }

    public function test_referrals_index_requires_permission(): void
    {
        $role = Role::firstOrCreate(['slug' => Role::ADMIN], ['name' => 'Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.referrals.index'))->assertForbidden();
    }

    public function test_referrals_index_lists_and_filters_by_officer(): void
    {
        $admin = $this->asSuperAdmin();
        $officerA = $this->makeOfficer();
        $customerA = $this->makeCustomer('Referred By A');
        Referral::create([
            'officer_id' => $officerA->id, 'customer_id' => $customerA->id,
            'referral_code' => $officerA->referral_code, 'registered_at' => now(),
        ]);

        Livewire::actingAs($admin)
            ->test(ReferralsIndex::class)
            ->assertSee('Referred By A')
            ->assertSee($officerA->name)
            ->set('officerFilter', (string) $officerA->id)
            ->assertSee('Referred By A');
    }
}
