<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DepartmentsAndDesignationsTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_a_department_can_be_created_and_duplicate_names_are_rejected(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('create')
            ->set('form.name', 'Marketing')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('departments', ['name' => 'Marketing']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('create')
            ->set('form.name', 'Marketing')
            ->call('save')
            ->assertHasErrors('form.name');

        $this->assertSame(1, Department::where('name', 'Marketing')->count());
    }

    public function test_a_sub_department_can_be_created_under_a_top_level_department(): void
    {
        $admin = $this->asSuperAdmin();
        $sales = Department::create(['name' => 'Sales']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('createSubDepartment', $sales->id)
            ->assertSet('form.parent_id', $sales->id)
            ->set('form.name', 'Retail Sales')
            ->call('save')
            ->assertHasNoErrors();

        $subDepartment = Department::where('name', 'Retail Sales')->first();
        $this->assertNotNull($subDepartment);
        $this->assertSame($sales->id, $subDepartment->parent_id);
        $this->assertTrue($subDepartment->isSubDepartment());
        $this->assertSame('Sales — Retail Sales', $subDepartment->fullName());
    }

    /**
     * Only one level of nesting is supported — a sub-department can never
     * itself be chosen as someone else's parent.
     */
    public function test_a_sub_department_cannot_be_selected_as_another_departments_parent(): void
    {
        $admin = $this->asSuperAdmin();
        $sales = Department::create(['name' => 'Sales']);
        $retail = Department::create(['name' => 'Retail Sales', 'parent_id' => $sales->id]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('create')
            ->set('form.name', 'Something')
            ->set('form.parent_id', $retail->id)
            ->call('save')
            ->assertHasErrors('form.parent_id');
    }

    /**
     * A department that already has sub-departments underneath it can't be
     * turned into a sub-department itself — that would create a second
     * level of nesting, which this app deliberately doesn't support.
     */
    public function test_a_department_with_existing_children_cannot_be_given_a_parent(): void
    {
        $admin = $this->asSuperAdmin();
        $sales = Department::create(['name' => 'Sales']);
        Department::create(['name' => 'Retail Sales', 'parent_id' => $sales->id]);
        $marketing = Department::create(['name' => 'Marketing']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('edit', $sales->id)
            ->set('form.parent_id', $marketing->id)
            ->call('save')
            ->assertHasErrors('form.parent_id');
    }

    public function test_a_department_cannot_be_its_own_parent(): void
    {
        $admin = $this->asSuperAdmin();
        $sales = Department::create(['name' => 'Sales']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('edit', $sales->id)
            ->set('form.parent_id', $sales->id)
            ->call('save')
            ->assertHasErrors('form.parent_id');
    }

    /**
     * Deleting a parent department must not cascade-delete its
     * sub-departments — they simply become top-level departments of their
     * own (parent_id is nullOnDelete), matching how deleting a department
     * never destroys the officers assigned to it either.
     */
    public function test_deleting_a_parent_department_makes_its_children_top_level_instead_of_deleting_them(): void
    {
        $admin = $this->asSuperAdmin();
        $sales = Department::create(['name' => 'Sales']);
        $retail = Department::create(['name' => 'Retail Sales', 'parent_id' => $sales->id]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('confirmDelete', $sales->id)
            ->call('delete');

        $this->assertDatabaseMissing('departments', ['id' => $sales->id]);
        $this->assertDatabaseHas('departments', ['id' => $retail->id]);
        $this->assertNull($retail->fresh()->parent_id);
    }

    /**
     * Filtering the Officers list by a parent department must also surface
     * officers assigned directly to its sub-departments — an admin
     * thinking in terms of "Sales" shouldn't need to know it has to also
     * pick "Retail Sales" and "Corporate Sales" separately to see everyone.
     */
    public function test_filtering_officers_by_a_parent_department_includes_its_sub_departments(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $sales = Department::create(['name' => 'Sales']);
        $retail = Department::create(['name' => 'Retail Sales', 'parent_id' => $sales->id]);
        $marketing = Department::create(['name' => 'Marketing']);

        $inSales = \App\Models\Officer::create([
            'user_id' => User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001'])->id,
            'employee_id' => 'EMP-0001', 'department_id' => $sales->id, 'status' => \App\Enums\OfficerStatus::Active,
        ]);
        $inRetail = \App\Models\Officer::create([
            'user_id' => User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0002'])->id,
            'employee_id' => 'EMP-0002', 'department_id' => $retail->id, 'status' => \App\Enums\OfficerStatus::Active,
        ]);
        $inMarketing = \App\Models\Officer::create([
            'user_id' => User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0003'])->id,
            'employee_id' => 'EMP-0003', 'department_id' => $marketing->id, 'status' => \App\Enums\OfficerStatus::Active,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Officers\Index::class)
            ->set('departmentFilter', (string) $sales->id)
            ->assertSee('EMP-0001')
            ->assertSee('EMP-0002')
            ->assertDontSee('EMP-0003');
    }

    public function test_a_designation_can_be_created_and_duplicate_names_are_rejected(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Designations\Index::class)
            ->call('create')
            ->set('form.name', 'Team Lead')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('designations', ['name' => 'Team Lead']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Designations\Index::class)
            ->call('create')
            ->set('form.name', 'Team Lead')
            ->call('save')
            ->assertHasErrors('form.name');

        $this->assertSame(1, Designation::where('name', 'Team Lead')->count());
    }

    public function test_deleting_a_department_unassigns_it_from_officers_rather_than_failing(): void
    {
        $admin = $this->asSuperAdmin();
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        $department = Department::create(['name' => 'Marketing']);
        $officerUser = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $officer = \App\Models\Officer::create([
            'user_id' => $officerUser->id,
            'employee_id' => 'EMP-0001',
            'department_id' => $department->id,
            'status' => \App\Enums\OfficerStatus::Active,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Departments\Index::class)
            ->call('confirmDelete', $department->id)
            ->call('delete');

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
        $this->assertNull($officer->fresh()->department_id);
    }
}
