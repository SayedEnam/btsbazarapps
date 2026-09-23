<?php

namespace Tests\Feature\Admin;

use App\Enums\OfficerStatus;
use App\Enums\UserStatus;
use App\Models\Department;
use App\Models\Officer;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OfficersCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_creating_an_officer_creates_a_user_account_role_and_referral_code_atomically(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);
        $department = Department::create(['name' => 'Marketing']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('create')
            ->set('form.name', 'New Officer')
            ->set('form.email', 'newofficer@example.com')
            ->set('form.username', 'new.officer')
            ->set('form.phone', '01700001111')
            ->set('form.password', 'password123')
            ->set('form.employee_id', 'EMP-9001')
            ->set('form.department_id', $department->id)
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('email', 'newofficer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(Role::MARKETING_OFFICER));
        $this->assertNotNull($user->referral_code);
        $this->assertMatchesRegularExpression('/^\d{4}$/', $user->referral_code);
        $this->assertSame(UserStatus::Active, $user->status);

        $officer = $user->officer;
        $this->assertNotNull($officer);
        $this->assertSame('EMP-9001', $officer->employee_id);
        $this->assertSame($department->id, $officer->department_id);
    }

    /**
     * Regression guard: User::generateReferralCode() must account for
     * soft-deleted users, or it recomputes a "next" number that collides
     * with an already-issued (but soft-deleted) referral_code and the
     * unique-index insert throws a raw SQLSTATE 500 instead of a friendly
     * validation error — this crashed officer creation in production.
     */
    public function test_creating_an_officer_generates_a_referral_code_that_skips_a_soft_deleted_officers_code(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $deletedOfficer = User::factory()->create(['referral_code' => '0001']);
        Officer::create(['user_id' => $deletedOfficer->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);
        $deletedOfficer->delete();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('create')
            ->set('form.name', 'Second Officer')
            ->set('form.email', 'secondofficer@example.com')
            ->set('form.username', 'second.officer')
            ->set('form.phone', '01700003333')
            ->set('form.password', 'password123')
            ->call('save')
            ->assertHasNoErrors();

        $newUser = User::where('email', 'secondofficer@example.com')->first();

        $this->assertNotNull($newUser);
        $this->assertSame('0002', $newUser->referral_code);
    }

    /**
     * The Employee ID field is pre-filled with the next sequential EMP-####
     * code when opening the "Add Officer" modal, and it accounts for
     * soft-deleted officers so a removed officer's ID is never reissued.
     */
    public function test_opening_the_create_form_auto_generates_the_next_employee_id(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $existingUser = User::factory()->create(['referral_code' => '0001']);
        Officer::create(['user_id' => $existingUser->id, 'employee_id' => 'EMP-0003', 'status' => OfficerStatus::Active]);

        $deletedUser = User::factory()->create(['referral_code' => '0002']);
        Officer::create(['user_id' => $deletedUser->id, 'employee_id' => 'EMP-0007', 'status' => OfficerStatus::Active])->delete();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('create')
            ->assertSet('form.employee_id', 'EMP-0008');
    }

    public function test_duplicate_employee_id_is_rejected(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $existingUser = User::factory()->create(['referral_code' => '0001']);
        Officer::create(['user_id' => $existingUser->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('create')
            ->set('form.name', 'Another Officer')
            ->set('form.email', 'another@example.com')
            ->set('form.username', 'another.officer')
            ->set('form.phone', '01700002222')
            ->set('form.password', 'password123')
            ->set('form.employee_id', 'EMP-0001')
            ->call('save')
            ->assertHasErrors('form.employee_id');

        $this->assertSame(1, Officer::count());
    }

    /**
     * Regression guard: editing an officer's profile (department, salary,
     * etc.) must never silently reactivate a suspended user account — that
     * was a real bug caught while writing OfficerForm, fixed before this
     * test existed to prove it stays fixed.
     */
    public function test_editing_an_officer_does_not_reactivate_a_suspended_user_account(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $officerUser = User::factory()->create(['status' => UserStatus::Suspended, 'phone' => '01700009999', 'referral_code' => '0001']);
        $officer = Officer::create(['user_id' => $officerUser->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('edit', $officer->id)
            ->set('form.basic_salary', '30000')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(UserStatus::Suspended, $officerUser->fresh()->status);
    }

    public function test_deleting_an_officer_removes_the_profile_but_keeps_the_user_account_and_role(): void
    {
        $admin = $this->asSuperAdmin();
        $officerRole = Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $officerUser = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $officerUser->roles()->attach($officerRole);
        $officer = Officer::create(['user_id' => $officerUser->id, 'employee_id' => 'EMP-0001', 'status' => OfficerStatus::Active]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->call('confirmDelete', $officer->id)
            ->call('delete');

        $this->assertSoftDeleted('officers', ['id' => $officer->id]);
        $this->assertDatabaseHas('users', ['id' => $officerUser->id, 'deleted_at' => null]);
        $this->assertTrue($officerUser->fresh()->hasRole(Role::MARKETING_OFFICER));
    }

    public function test_officers_index_requires_officers_view_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.officers.index'))->assertForbidden();
    }
}
