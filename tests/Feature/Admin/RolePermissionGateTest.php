<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionGateTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_do_anything_without_explicit_permissions(): void
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('anything.not.seeded'));
    }

    public function test_a_user_only_gets_abilities_granted_to_their_role(): void
    {
        $permission = Permission::create(['name' => 'View Users', 'slug' => 'users.view', 'group' => 'Users']);
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $role->permissions()->attach($permission);

        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->assertTrue($user->can('users.view'));
        $this->assertFalse($user->can('users.delete'));
    }

    public function test_a_user_with_no_roles_has_no_permissions(): void
    {
        $user = User::factory()->create(['status' => UserStatus::Active]);

        $this->assertFalse($user->can('users.view'));
    }
}
