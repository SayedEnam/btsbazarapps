<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function asAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_a_user_can_be_created_with_a_role(): void
    {
        $admin = $this->asAdmin();
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('create')
            ->set('form.name', 'Jane Doe')
            ->set('form.email', 'jane@example.com')
            ->set('form.username', 'jane.doe')
            ->set('form.password', 'password123')
            ->set('form.role_ids', [$role->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertTrue(User::where('email', 'jane@example.com')->first()->hasRole(Role::ADMIN));
    }

    /** Same class of bug as the Role regression test: duplicate email must fail cleanly, not crash. */
    public function test_creating_a_user_with_a_duplicate_email_fails_validation_instead_of_crashing(): void
    {
        $admin = $this->asAdmin();
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('create')
            ->set('form.name', 'Someone Else')
            ->set('form.email', 'taken@example.com')
            ->set('form.username', 'someone.else')
            ->set('form.password', 'password123')
            ->call('save')
            ->assertHasErrors('form.email');

        $this->assertSame(1, User::where('email', 'taken@example.com')->count());
    }

    /**
     * The user-facing ask: an error appears the moment a field is left
     * invalid, and disappears again the moment it's fixed — no need to
     * submit the whole form to find out. `set()` in a Livewire test fires
     * the same `updated()` hook a real blur event does.
     */
    public function test_a_field_error_appears_and_then_clears_as_the_field_is_corrected(): void
    {
        $admin = $this->asAdmin();

        $component = Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('create')
            ->set('form.email', 'not-an-email')
            ->assertHasErrors('form.email');

        $message = $component->errors()->first('form.email');
        $this->assertStringContainsString('email address', $message);

        $component
            ->set('form.email', 'valid@example.com')
            ->assertHasNoErrors('form.email');
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $admin = $this->asAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('confirmDelete', $admin->id)
            ->call('delete');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_one_of_two_super_admins_can_be_deleted_but_the_last_one_cannot(): void
    {
        $superAdminRole = Role::where('slug', Role::SUPER_ADMIN)->first()
            ?? Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);

        // A regular Admin (not Super Admin) holding `users.delete` does the deleting, so this
        // proves the Policy invariant itself — not Super Admin's Gate::before bypass, which
        // (per the fix above) no longer applies to bare-word abilities like `delete` anyway.
        $viewPermission = Permission::create(['name' => 'View Users', 'slug' => 'users.view', 'group' => 'Users']);
        $deletePermission = Permission::create(['name' => 'Delete Users', 'slug' => 'users.delete', 'group' => 'Users']);
        $adminRole = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $adminRole->permissions()->attach([$viewPermission->id, $deletePermission->id]);

        $actor = User::factory()->create(['status' => UserStatus::Active]);
        $actor->roles()->attach($adminRole);

        $superAdminA = User::factory()->create(['status' => UserStatus::Active]);
        $superAdminA->roles()->attach($superAdminRole);
        $superAdminB = User::factory()->create(['status' => UserStatus::Active]);
        $superAdminB->roles()->attach($superAdminRole);

        // Two Super Admins exist: deleting one is fine, one remains.
        Livewire::actingAs($actor)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('confirmDelete', $superAdminA->id)
            ->call('delete');

        $this->assertSoftDeleted('users', ['id' => $superAdminA->id]);

        // Only superAdminB is left: deleting it must be blocked, even though the actor
        // otherwise has the `users.delete` permission.
        Livewire::actingAs($actor)
            ->test(\App\Livewire\Admin\Users\Index::class)
            ->call('confirmDelete', $superAdminB->id)
            ->call('delete');

        $this->assertDatabaseHas('users', ['id' => $superAdminB->id, 'deleted_at' => null]);
    }
}
