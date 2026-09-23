<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RolesCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function asAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_a_role_can_be_created_with_permissions(): void
    {
        $admin = $this->asAdmin();
        $permission = Permission::create(['name' => 'View Users', 'slug' => 'users.view', 'group' => 'Users']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Roles\Index::class)
            ->call('create')
            ->set('form.name', 'Support Agent')
            ->set('form.description', 'Handles support tickets')
            ->set('form.permission_ids', [$permission->id])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('roles', ['name' => 'Support Agent', 'slug' => 'support-agent']);
        $this->assertTrue(Role::where('name', 'Support Agent')->first()->hasPermission('users.view'));
    }

    /**
     * Regression test: RoleForm previously declared both a #[Validate] attribute
     * and a rules() method for `name`. Livewire's array_merge silently let the
     * attribute's plain rule win over rules()'s uniqueness check, so a duplicate
     * name sailed through validation and blew up on the DB's unique constraint
     * instead of producing a normal validation error.
     */
    public function test_creating_a_role_with_a_duplicate_name_fails_validation_instead_of_crashing(): void
    {
        $admin = $this->asAdmin();
        Role::create(['name' => 'Support Agent', 'slug' => 'support-agent']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Roles\Index::class)
            ->call('create')
            ->set('form.name', 'Support Agent')
            ->call('save')
            ->assertHasErrors('form.name');

        $this->assertSame(1, Role::where('name', 'Support Agent')->count());
    }

    public function test_a_system_role_cannot_be_deleted(): void
    {
        $admin = $this->asAdmin();
        $superAdminRole = Role::where('slug', Role::SUPER_ADMIN)->first();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Roles\Index::class)
            ->call('confirmDelete', $superAdminRole->id)
            ->call('delete');

        $this->assertDatabaseHas('roles', ['id' => $superAdminRole->id]);
    }
}
