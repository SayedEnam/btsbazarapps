<?php

namespace Tests\Feature\Admin;

use App\Actions\ChangeApplicationStatus;
use App\Actions\SubmitApplication;
use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\Dashboard;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    protected function makeCustomer(): Customer
    {
        $role = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Pending]);
    }

    protected function makePackage(): Package
    {
        return Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
    }

    /**
     * The dashboard used to only ever show Users/Roles/Permissions counts
     * with a static "not built yet" banner — real once every module shipped.
     * This locks in that it now reflects real business data end to end.
     */
    public function test_dashboard_shows_real_business_metrics_from_seeded_data(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $package = $this->makePackage();

        $pending = app(SubmitApplication::class)($customer, $package);

        $approvedCustomer = $this->makeCustomer();
        $approvedApplication = app(SubmitApplication::class)($approvedCustomer, $package);
        app(ChangeApplicationStatus::class)($approvedApplication, ApplicationStatus::Approved, $admin);

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertSee('Active Customers')
            ->assertSee('Pending Applications')
            ->assertSee('1') // one pending application
            ->assertSee('1,000.00') // total revenue from the one approved application
            ->assertSee($pending->customer->user->name) // shows up in "Applications Needing Review"
            ->assertDontSee('Foundation module');
    }

    /**
     * Real production crash: a pending application's customer had their User
     * account soft-deleted (e.g. via the admin Users screen, which is
     * soft-delete-only by design) while the Application and Customer rows
     * were left in place. The "Applications Needing Review" panel assumed
     * `$application->customer->user` always resolves and crashed with
     * "Attempt to read property 'name' on null". Same risk existed for a
     * soft-deleted Package. This proves the dashboard renders instead of
     * throwing, in both cases.
     */
    public function test_dashboard_does_not_crash_when_a_pending_applications_customer_user_is_soft_deleted(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $package = $this->makePackage();
        app(SubmitApplication::class)($customer, $package);

        $customer->user->delete();

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertSee('Unknown customer')
            ->assertOk();
    }

    public function test_dashboard_does_not_crash_when_a_pending_applications_package_is_soft_deleted(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $package = $this->makePackage();
        app(SubmitApplication::class)($customer, $package);

        $package->delete();

        Livewire::actingAs($admin)
            ->test(Dashboard::class)
            ->assertSee('Unknown package')
            ->assertOk();
    }
}
