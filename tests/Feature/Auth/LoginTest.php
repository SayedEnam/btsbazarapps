<?php

namespace Tests\Feature\Auth;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_super_admin_can_login_and_reach_the_admin_dashboard(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $response = Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('form.login', $user->email)
            ->set('form.password', 'password')
            ->call('login');

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_a_user_can_login_with_their_username_instead_of_email(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active, 'username' => 'thesuperadmin']);
        $user->roles()->attach($role);

        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('form.login', 'thesuperadmin')
            ->set('form.password', 'password')
            ->call('login')
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $user = User::factory()->create();

        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('form.login', $user->email)
            ->set('form.password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('form.login');

        $this->assertGuest();
    }

    public function test_a_non_admin_role_cannot_reach_the_admin_area(): void
    {
        $role = Role::create(['name' => 'Customer', 'slug' => Role::CUSTOMER, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
