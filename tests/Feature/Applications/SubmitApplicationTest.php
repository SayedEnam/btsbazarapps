<?php

namespace Tests\Feature\Applications;

use App\Actions\SubmitApplication;
use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Customer\Applications\Create;
use App\Models\Customer;
use App\Models\Officer;
use App\Models\Package;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class SubmitApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCustomer(): Customer
    {
        $role = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Pending]);
    }

    protected function makePackage(float $price = 1000): Package
    {
        return Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'price' => $price, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
    }

    public function test_submitting_an_application_snapshots_the_current_package_price(): void
    {
        $customer = $this->makeCustomer();
        $package = $this->makePackage(1000);

        $application = app(SubmitApplication::class)($customer, $package);

        $this->assertSame('1000.00', $application->package_price);
        $this->assertSame(ApplicationStatus::Pending, $application->status);
        $this->assertNotNull($application->application_number);
        $this->assertStringStartsWith('APP-'.now()->year.'-', $application->application_number);

        // Changing the package price afterward must never rewrite history.
        $package->update(['price' => 1500]);
        $this->assertSame('1000.00', $application->fresh()->package_price);
    }

    public function test_a_customer_cannot_submit_a_second_application_while_one_is_pending(): void
    {
        $customer = $this->makeCustomer();
        $package = $this->makePackage();

        app(SubmitApplication::class)($customer, $package);

        $this->expectException(RuntimeException::class);
        app(SubmitApplication::class)($customer, $package);
    }

    public function test_submitting_through_a_referred_officer_sets_officer_id_and_notifies_them(): void
    {
        Notification::fake();

        $officerRole = Role::firstOrCreate(['slug' => Role::MARKETING_OFFICER], ['name' => 'Marketing Officer', 'is_system' => true]);
        $officer = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $officer->roles()->attach($officerRole);
        Officer::create(['user_id' => $officer->id, 'employee_id' => 'EMP-0001']);

        $customer = $this->makeCustomer();
        Referral::create([
            'officer_id' => $officer->id, 'customer_id' => $customer->id,
            'referral_code' => '0001', 'registered_at' => now(),
        ]);

        $package = $this->makePackage();
        $application = app(SubmitApplication::class)($customer, $package);

        $this->assertSame($officer->id, $application->officer_id);
        Notification::assertSentTo($officer, \App\Notifications\NewApplicationSubmitted::class);
    }

    public function test_a_customer_without_a_referral_officer_can_still_apply_directly(): void
    {
        $customer = $this->makeCustomer();
        $package = $this->makePackage();

        $application = app(SubmitApplication::class)($customer, $package);

        $this->assertNull($application->officer_id);
        $this->assertSame(ApplicationStatus::Pending, $application->status);
    }

    public function test_the_apply_page_blocks_a_customer_who_already_has_an_open_application(): void
    {
        $customer = $this->makeCustomer();
        $package = $this->makePackage();
        app(SubmitApplication::class)($customer, $package);

        Livewire::actingAs($customer->user)
            ->test(Create::class)
            ->assertRedirect(route('customer.dashboard'));
    }
}
