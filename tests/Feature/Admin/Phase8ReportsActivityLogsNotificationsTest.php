<?php

namespace Tests\Feature\Admin;

use App\Actions\ChangeApplicationStatus;
use App\Actions\SubmitApplication;
use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\PackageStatus;
use App\Enums\UserStatus;
use App\Livewire\Admin\ActivityLogs\Index as ActivityLogsIndex;
use App\Livewire\Admin\NotificationBell;
use App\Livewire\Admin\Notifications\Index as NotificationsIndex;
use App\Livewire\Admin\Reports\Index as ReportsIndex;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class Phase8ReportsActivityLogsNotificationsTest extends TestCase
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

    public function test_approving_an_application_writes_an_activity_log_entry(): void
    {
        $admin = $this->asSuperAdmin();
        $this->actingAs($admin);
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'module' => 'Applications',
            'action' => 'approved',
            'model_type' => $application->getMorphClass(),
            'model_id' => $application->id,
        ]);
    }

    public function test_activity_logs_index_requires_permission(): void
    {
        $role = Role::firstOrCreate(['slug' => Role::ADMIN], ['name' => 'Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.activity-logs.index'))->assertForbidden();
    }

    public function test_activity_logs_index_lists_and_filters_by_module(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        Livewire::actingAs($admin)
            ->test(ActivityLogsIndex::class)
            ->assertSee('Applications')
            ->set('moduleFilter', 'Applications')
            ->assertSee($application->application_number)
            ->set('moduleFilter', 'Payroll')
            ->assertDontSee($application->application_number);
    }

    public function test_reports_index_requires_permission(): void
    {
        $role = Role::firstOrCreate(['slug' => Role::ADMIN], ['name' => 'Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.reports.index'))->assertForbidden();
    }

    public function test_reports_customers_tab_lists_seeded_customer(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();

        Livewire::actingAs($admin)
            ->test(ReportsIndex::class)
            ->set('reportType', 'customers')
            ->assertSee($customer->user->name);
    }

    public function test_reports_applications_csv_export_streams_the_filtered_rows(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        $testable = Livewire::actingAs($admin)
            ->test(ReportsIndex::class)
            ->set('reportType', 'applications')
            ->call('export');

        $testable->assertFileDownloaded();

        $content = base64_decode(data_get($testable->effects, 'download.content'));

        $this->assertStringContainsString($application->application_number, $content);
        $this->assertStringContainsString('Application #', $content); // header row
    }

    public function test_application_approval_notifies_super_admin_and_it_appears_on_the_notification_bell(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());

        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'type' => ApplicationStatusChanged::class,
        ]);

        Livewire::actingAs($admin)
            ->test(NotificationBell::class)
            ->assertSee('1'); // unread badge count
    }

    public function test_notification_bell_mark_all_as_read_clears_the_unread_count(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        Livewire::actingAs($admin)
            ->test(NotificationBell::class)
            ->assertSee('Mark all read')
            ->call('markAllAsRead')
            ->assertDontSee('Mark all read');

        $this->assertSame(0, $admin->fresh()->unreadNotifications()->count());
    }

    public function test_notifications_index_page_lists_and_marks_as_read(): void
    {
        $admin = $this->asSuperAdmin();
        $customer = $this->makeCustomer();
        $application = app(SubmitApplication::class)($customer, $this->makePackage());
        app(ChangeApplicationStatus::class)($application, ApplicationStatus::Approved, $admin);

        $notificationId = $admin->fresh()->notifications()->first()->id;

        Livewire::actingAs($admin)
            ->test(NotificationsIndex::class)
            ->assertSee('Application')
            ->call('markAsRead', $notificationId);

        $this->assertNotNull($admin->fresh()->notifications()->first()->read_at);
    }
}
