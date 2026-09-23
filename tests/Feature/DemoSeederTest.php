<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Officer;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_full_seeder_produces_at_least_20_demo_users_across_every_role_and_status(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertGreaterThanOrEqual(20, User::count());

        foreach ([Role::ADMIN, Role::MARKETING_OFFICER, Role::CUSTOMER] as $slug) {
            $role = Role::where('slug', $slug)->firstOrFail();
            $this->assertGreaterThan(0, $role->users()->count(), "Expected at least one user with role [{$slug}].");
        }

        $this->assertGreaterThan(0, User::where('status', 'active')->count());
        $this->assertGreaterThan(0, User::where('status', 'inactive')->count());
        $this->assertGreaterThan(0, User::where('status', 'suspended')->count());
    }

    public function test_seeding_is_idempotent_and_does_not_duplicate_demo_users(): void
    {
        $this->seed(DatabaseSeeder::class);
        $countAfterFirstRun = User::count();

        $this->seed(DatabaseSeeder::class);
        $countAfterSecondRun = User::count();

        $this->assertSame($countAfterFirstRun, $countAfterSecondRun);
    }

    /**
     * Regression test for a real bug: Officer/Customer are both soft-delete
     * models, and their seeders used updateOrCreate() without withTrashed().
     * updateOrCreate()'s lookup is scoped to non-trashed rows by default, so
     * once a demo officer was soft-deleted (exactly what the admin Officers
     * screen's delete button does), reseeding tried a fresh INSERT that
     * collided with the trashed row's unique user_id — a genuine
     * UniqueConstraintViolationException reported from a live reseed.
     */
    public function test_reseeding_after_a_soft_deleted_officer_does_not_throw_and_restores_it(): void
    {
        $this->seed(DatabaseSeeder::class);

        $officer = Officer::firstOrFail();
        $officer->delete();
        $this->assertSoftDeleted($officer);

        $this->seed(DatabaseSeeder::class);

        $this->assertNotSoftDeleted($officer->fresh());
        $this->assertSame(1, Officer::withTrashed()->where('user_id', $officer->user_id)->count());
    }

    public function test_reseeding_after_a_soft_deleted_customer_does_not_throw_and_restores_it(): void
    {
        $this->seed(DatabaseSeeder::class);

        $customer = Customer::firstOrFail();
        $customer->delete();
        $this->assertSoftDeleted($customer);

        $this->seed(DatabaseSeeder::class);

        $this->assertNotSoftDeleted($customer->fresh());
        $this->assertSame(1, Customer::withTrashed()->where('user_id', $customer->user_id)->count());
    }
}
