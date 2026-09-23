<?php

namespace Tests\Feature\Customer;

use App\Enums\CustomerStatus;
use App\Enums\UserStatus;
use App\Models\Customer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function makeCustomer(): User
    {
        $role = Role::create(['name' => 'Customer', 'slug' => Role::CUSTOMER, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);
        Customer::create(['user_id' => $user->id, 'status' => CustomerStatus::Active]);

        return $user;
    }

    public function test_a_customer_can_view_their_own_dashboard(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->get(route('customer.dashboard'))
            ->assertOk()
            ->assertSee($customer->name);
    }

    public function test_a_customer_cannot_reach_the_admin_area(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_an_admin_cannot_reach_the_customer_area(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $admin = User::factory()->create(['status' => UserStatus::Active]);
        $admin->roles()->attach($role);

        $this->actingAs($admin)
            ->get(route('customer.dashboard'))
            ->assertForbidden();
    }

    public function test_a_guest_is_redirected_to_login_from_the_customer_dashboard(): void
    {
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));
    }

    public function test_customer_dashboard_only_ever_shows_the_logged_in_customers_own_data(): void
    {
        // Two customers exist; the one logged in must never see the other's data.
        $customerA = $this->makeCustomer();
        $customerA->update(['name' => 'Customer A Name']);

        $role = Role::where('slug', Role::CUSTOMER)->first();
        $userB = User::factory()->create(['name' => 'Customer B Name', 'status' => UserStatus::Active]);
        $userB->roles()->attach($role);
        Customer::create(['user_id' => $userB->id, 'status' => CustomerStatus::Active]);

        $this->actingAs($customerA)
            ->get(route('customer.dashboard'))
            ->assertSee('Customer A Name')
            ->assertDontSee('Customer B Name');
    }
}
