<?php

namespace Tests\Feature\Applications;

use App\Actions\ChangeApplicationStatus;
use App\Actions\SubmitApplication;
use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCustomer(): Customer
    {
        $role = Role::firstOrCreate(['slug' => Role::CUSTOMER], ['name' => 'Customer', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Pending]);
    }

    protected function makeAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
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

    public function test_approving_an_application_activates_the_customer_and_notifies_them(): void
    {
        Notification::fake();

        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        $result = app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $this->assertSame(ApplicationStatus::Approved, $result->status);
        $this->assertSame($admin->id, $result->reviewed_by);
        $this->assertNotNull($result->reviewed_at);
        $this->assertSame(CustomerStatus::Active, $customer->fresh()->status);

        $this->assertDatabaseHas('application_status_histories', [
            'application_id' => $application->id, 'old_status' => 'pending', 'new_status' => 'approved',
        ]);

        Notification::assertSentTo($customer->user, \App\Notifications\ApplicationStatusChanged::class);
    }

    public function test_rejecting_an_application_stores_the_reason_and_does_not_touch_customer_status(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $customer->update(['status' => CustomerStatus::Active]); // already active via another application
        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        $result = app(ChangeApplicationStatus::class)($application, ApplicationStatus::Rejected, $admin, 'Incomplete documents.');

        $this->assertSame(ApplicationStatus::Rejected, $result->status);
        $this->assertSame('Incomplete documents.', $result->rejection_reason);
        // Rejection never demotes a customer who is already active elsewhere (spec section 50).
        $this->assertSame(CustomerStatus::Active, $customer->fresh()->status);
    }

    public function test_an_already_approved_application_cannot_be_approved_again(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $this->expectException(RuntimeException::class);
        app(ChangeApplicationStatus::class)($application->fresh(), ApplicationStatus::Approved, $admin);
    }

    public function test_a_rejected_application_is_terminal_and_cannot_be_reopened(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Rejected, $admin, 'No.');

        $this->expectException(RuntimeException::class);
        app(ChangeApplicationStatus::class)($application->fresh(), ApplicationStatus::UnderReview, $admin);
    }

    public function test_an_approved_application_can_still_be_cancelled_by_an_admin(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $result = app(ChangeApplicationStatus::class)($application->fresh(), ApplicationStatus::Cancelled, $admin, 'Mistaken approval.');

        $this->assertSame(ApplicationStatus::Cancelled, $result->status);
    }
}
