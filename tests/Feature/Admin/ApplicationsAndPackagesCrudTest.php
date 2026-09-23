<?php

namespace Tests\Feature\Admin;

use App\Actions\SubmitApplication;
use App\Enums\CustomerStatus;
use App\Enums\OfficerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\Applications\Index as AdminApplicationsIndex;
use App\Livewire\Admin\Packages\Index as AdminPackagesIndex;
use App\Models\Customer;
use App\Models\Officer;
use App\Models\Package;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApplicationsAndPackagesCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    protected function makePackage(): Package
    {
        return Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
    }

    protected function makeCustomer(): Customer
    {
        $role = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Pending]);
    }

    // --- Packages ---

    public function test_a_package_can_be_created_with_a_decimal_price(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(AdminPackagesIndex::class)
            ->call('create')
            ->set('form.name', 'Premium Membership')
            ->set('form.code', 'PREM-2000')
            ->set('form.price', '2000.50')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('packages', ['code' => 'PREM-2000', 'price' => 2000.50]);
    }

    public function test_duplicate_package_code_is_rejected(): void
    {
        $admin = $this->asSuperAdmin();
        $this->makePackage();

        Livewire::actingAs($admin)
            ->test(AdminPackagesIndex::class)
            ->call('create')
            ->set('form.name', 'Another Package')
            ->set('form.code', 'STD-1000')
            ->set('form.price', '500')
            ->call('save')
            ->assertHasErrors('form.code');
    }

    public function test_deleting_a_package_does_not_change_the_price_already_stored_on_an_existing_application(): void
    {
        $admin = $this->asSuperAdmin();
        $package = $this->makePackage();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $package);

        Livewire::actingAs($admin)
            ->test(AdminPackagesIndex::class)
            ->call('confirmDelete', $package->id)
            ->call('delete');

        $this->assertSoftDeleted('packages', ['id' => $package->id]);
        $this->assertSame('1000.00', $application->fresh()->package_price);
    }

    // --- Applications ---

    public function test_admin_can_reassign_an_application_to_a_different_officer_without_touching_the_referral(): void
    {
        $admin = $this->asSuperAdmin();
        $officerRole = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);

        $originalOfficer = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $originalOfficer->roles()->attach($officerRole);
        Officer::create(['user_id' => $originalOfficer->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        $newOfficer = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0002']);
        $newOfficer->roles()->attach($officerRole);
        Officer::create(['user_id' => $newOfficer->id, 'employee_id' => 'EMP-0002', 'status' => OfficerStatus::Active]);

        $customer = $this->makeCustomer();
        Referral::create([
            'officer_id' => $originalOfficer->id, 'customer_id' => $customer->id,
            'referral_code' => '0001', 'registered_at' => now(),
        ]);

        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        $this->assertSame($originalOfficer->id, $application->officer_id);

        Livewire::actingAs($admin)
            ->test(AdminApplicationsIndex::class)
            ->call('confirmReassign', $application->id)
            ->set('newOfficerId', (string) $newOfficer->id)
            ->call('reassign')
            ->assertHasNoErrors();

        $this->assertSame($newOfficer->id, $application->fresh()->officer_id);
        // The permanent referral relationship is untouched by reassignment.
        $this->assertSame($originalOfficer->id, $customer->fresh()->referral->officer_id);
    }

    /**
     * Regression guard: approving/rejecting an application whose customer's
     * underlying User account has since been soft-deleted (e.g. an admin
     * removed a duplicate/spam login while leaving the membership record
     * intact) must not 500 — customer?->user?->notify(...) needs both
     * null-safe operators, since either link can be missing independently.
     */
    public function test_approving_an_application_whose_customer_user_was_soft_deleted_does_not_crash(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $customer->user->delete();

        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        Livewire::actingAs($admin)
            ->test(AdminApplicationsIndex::class)
            ->call('approve', $application->id);

        $this->assertSame(\App\Enums\ApplicationStatus::Approved, $application->fresh()->status);
    }

    public function test_applications_index_requires_applications_view_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.applications.index'))->assertForbidden();
    }
}
