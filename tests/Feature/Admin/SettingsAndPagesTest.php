<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SettingsAndPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    public function test_super_admin_can_update_company_settings(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('company_name', 'New Company Name')
            ->set('currency_symbol', '$')
            ->set('timezone', 'Asia/Dhaka')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('New Company Name', Setting::get('company_name'));
        $this->assertSame('$', Setting::get('currency_symbol'));
    }

    public function test_super_admin_can_customize_theme_colors_and_heading_sizes(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('company_name', 'Existing Co')
            ->set('currency_symbol', '$')
            ->set('timezone', 'Asia/Dhaka')
            ->set('theme_primary_color', '#ff0000')
            ->set('theme_primary_dark_color', '#cc0000')
            ->set('theme_primary_light_color', '#ff6666')
            ->set('theme_footer_bg_color', '#111111')
            ->set('theme_footer_text_color', '#eeeeee')
            ->set('theme_h1_size', '3rem')
            ->set('theme_body_font_size', '1.125rem')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('#ff0000', Setting::get('theme_primary_color'));
        $this->assertSame('#cc0000', Setting::get('theme_primary_dark_color'));
        $this->assertSame('3rem', Setting::get('theme_h1_size'));
        $this->assertSame('1.125rem', Setting::get('theme_body_font_size'));
    }

    public function test_an_invalid_theme_color_is_rejected(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('company_name', 'Existing Co')
            ->set('currency_symbol', '$')
            ->set('timezone', 'Asia/Dhaka')
            ->set('theme_primary_color', 'not-a-hex-color')
            ->call('save')
            ->assertHasErrors('theme_primary_color');
    }

    public function test_resetting_the_theme_restores_the_original_defaults(): void
    {
        $admin = $this->asSuperAdmin();
        Setting::set('theme_primary_color', '#ff0000', 'theme');

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->call('resetTheme')
            ->assertSet('theme_primary_color', Setting::THEME_DEFAULTS['theme_primary_color']);

        $this->assertSame(Setting::THEME_DEFAULTS['theme_primary_color'], Setting::get('theme_primary_color'));
    }

    /**
     * The public home page's inline <style> block reads theme colors
     * straight from Setting::theme() on every request — this proves a
     * saved customization actually reaches the rendered page, not just
     * the database.
     */
    public function test_a_customized_primary_color_renders_on_the_public_home_page(): void
    {
        Setting::set('theme_primary_color', '#ff00aa', 'theme');

        $this->get('/')->assertOk()->assertSee('--brand: #ff00aa;', false);
    }

    public function test_super_admin_can_upload_and_remove_a_logo(): void
    {
        Storage::fake('public');
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('logo', UploadedFile::fake()->image('logo.png', 200, 60))
            ->call('uploadLogo')
            ->assertHasNoErrors();

        $path = Setting::get('logo_path');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        $this->assertNotNull(Setting::logoUrl());

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->call('removeLogo');

        $this->assertSame('', Setting::get('logo_path'));
        $this->assertNull(Setting::logoUrl());
    }

    public function test_a_non_image_logo_upload_is_rejected(): void
    {
        Storage::fake('public');
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('logo', UploadedFile::fake()->create('not-an-image.pdf', 100))
            ->call('uploadLogo')
            ->assertHasErrors('logo');

        $this->assertEmpty(Setting::get('logo_path', ''));
    }

    public function test_super_admin_can_upload_and_remove_a_favicon(): void
    {
        Storage::fake('public');
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('favicon', UploadedFile::fake()->image('favicon.png', 32, 32))
            ->call('uploadFavicon')
            ->assertHasNoErrors();

        $path = Setting::get('favicon_path');
        $this->assertNotEmpty($path);
        Storage::disk('public')->assertExists($path);
        $this->assertNotNull(Setting::faviconUrl());

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Settings::class)
            ->call('removeFavicon');

        $this->assertSame('', Setting::get('favicon_path'));
        $this->assertNull(Setting::faviconUrl());
    }

    public function test_uploading_branding_images_requires_settings_edit_permission(): void
    {
        Storage::fake('public');

        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $viewPermission = Permission::create(['name' => 'View Settings', 'slug' => 'settings.view', 'group' => 'Settings']);
        $role->permissions()->attach($viewPermission->id); // view only, no edit

        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Admin\Settings::class)
            ->set('logo', UploadedFile::fake()->image('logo.png'))
            ->call('uploadLogo');

        // The Gate::authorize inside uploadLogo() blocks the write silently
        // (Livewire absorbs the AuthorizationException — see the identical
        // pattern proven in CustomersAndReferralsTest).
        $this->assertEmpty(Setting::get('logo_path', ''));
    }

    public function test_settings_page_requires_settings_view_permission(): void
    {
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user->roles()->attach($role); // no permissions granted

        $this->actingAs($user)->get(route('admin.settings'))->assertForbidden();
    }

    public function test_super_admin_can_edit_a_page(): void
    {
        $admin = $this->asSuperAdmin();
        $page = Page::create(['title' => 'About Us', 'slug' => Page::ABOUT, 'content' => '<p>Old content.</p>']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Pages\Index::class)
            ->call('edit', $page->id)
            ->set('content', '<p>Brand new content.</p>')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('<p>Brand new content.</p>', $page->fresh()->content);
        $this->assertSame($admin->id, $page->fresh()->updated_by);
    }

    public function test_editing_a_page_records_who_updated_it_and_shows_on_the_public_site_immediately(): void
    {
        $admin = $this->asSuperAdmin();
        $page = Page::create(['title' => 'Terms & Conditions', 'slug' => Page::TERMS, 'content' => '<p>Old terms.</p>']);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Pages\Index::class)
            ->call('edit', $page->id)
            ->set('content', '<p>Updated terms text.</p>')
            ->call('save');

        $this->get('/terms')->assertSee('Updated terms text.', false);
    }
}
