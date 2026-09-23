<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class QaChecklistTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::firstOrCreate(['slug' => Role::SUPER_ADMIN], ['name' => 'Super Admin', 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_qa_checklist_index_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.qa-checklist.index'))->assertForbidden();
    }

    public function test_qa_checklist_index_renders_every_module_screenshot_that_exists_on_disk(): void
    {
        $admin = $this->asSuperAdmin();

        $response = $this->actingAs($admin)->get(route('admin.qa-checklist.index'));

        $response->assertOk();
        $response->assertSee('QA Checklist');

        // Every screenshot referenced by the page must actually exist on
        // disk — this is a static documentation page, so a missing file
        // would silently render as a broken image with nothing to catch it
        // otherwise.
        preg_match_all('/qa-checklist\/([a-z0-9\-]+\.png)/', $response->getContent(), $matches);
        $this->assertNotEmpty($matches[1], 'Expected the response to reference at least one screenshot.');

        foreach (array_unique($matches[1]) as $filename) {
            $this->assertTrue(
                File::exists(public_path("images/qa-checklist/{$filename}")),
                "Screenshot referenced on the QA Checklist page is missing from disk: {$filename}"
            );
        }
    }
}
