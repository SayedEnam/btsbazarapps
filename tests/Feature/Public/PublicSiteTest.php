<?php

namespace Tests\Feature\Public;

use App\Enums\PackageStatus;
use App\Models\ContactMessage;
use App\Models\Package;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_the_active_package_price(): void
    {
        Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'description' => 'Core package.', 'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);

        $this->get('/')->assertOk()->assertSee('Standard Membership')->assertSee('1,000');
    }

    /**
     * The package's uploaded image was stored and shown in the admin list,
     * but neither public template ever referenced imageUrl() — a package
     * photo just silently never appeared on the site. Guards both pages.
     */
    public function test_package_image_shows_on_both_home_and_packages_pages(): void
    {
        Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'description' => 'Core package.', 'price' => 1000, 'status' => PackageStatus::Active,
            'sort_order' => 1, 'image' => 'packages/standard.jpg',
        ]);

        $this->get('/')->assertOk()->assertSee('storage/packages/standard.jpg', false);
        $this->get('/packages')->assertOk()->assertSee('storage/packages/standard.jpg', false);
    }

    /**
     * The home page used to show only $packages->first() in a single
     * featured card — changed to show every active package (grid,
     * matching the /packages page) after a report that "new packages
     * aren't showing on the frontend" turned out to be exactly this
     * by-design single-package limitation.
     */
    public function test_home_page_shows_every_active_package_not_just_the_first(): void
    {
        Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'description' => 'Core package.', 'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
        Package::create([
            'name' => 'Gold Membership', 'slug' => 'gold-membership', 'code' => 'GLD-2000',
            'description' => 'Premium package.', 'price' => 2000, 'status' => PackageStatus::Active, 'sort_order' => 2,
        ]);

        $this->get('/')->assertOk()
            ->assertSee('Standard Membership')->assertSee('1,000')
            ->assertSee('Gold Membership')->assertSee('2,000');
    }

    public function test_home_page_hides_inactive_packages(): void
    {
        Package::create([
            'name' => 'Standard Membership', 'slug' => 'standard-membership', 'code' => 'STD-1000',
            'description' => 'Core package.', 'price' => 1000, 'status' => PackageStatus::Active, 'sort_order' => 1,
        ]);
        Package::create([
            'name' => 'Retired Package', 'slug' => 'retired-package', 'code' => 'OLD-1',
            'description' => 'No longer offered.', 'price' => 500, 'status' => PackageStatus::Inactive, 'sort_order' => 2,
        ]);

        $response = $this->get('/packages');

        $response->assertOk()->assertSee('Standard Membership')->assertDontSee('Retired Package');
    }

    /**
     * Regression test: these Page rows deliberately omit `meta_description`
     * (a nullable column), because Blade's `@section('name', $value)` uses
     * `null` as an internal sentinel meaning "capture this as a block" — so
     * `@section('meta_description', $page->meta_description)` with a NULL
     * value silently opened an ob_start() that was never closed (no matching
     * @endsection exists for the inline form). The view now coalesces to ''
     * before handing it to @section. Asserting ob_get_level() is unchanged
     * is what actually catches a regression here; assertOk() alone would not.
     */
    public function test_about_page_renders_seeded_content(): void
    {
        Page::create(['title' => 'About Us', 'slug' => Page::ABOUT, 'content' => '<p>Custom about content.</p>']);

        $obLevelBefore = ob_get_level();

        $this->get('/about')->assertOk()->assertSee('Custom about content.', false);

        $this->assertSame($obLevelBefore, ob_get_level(), 'Rendering /about leaked an output buffer.');
    }

    public function test_terms_page_renders_seeded_content(): void
    {
        Page::create(['title' => 'Terms & Conditions', 'slug' => Page::TERMS, 'content' => '<p>Custom terms content.</p>']);

        $this->get('/terms')->assertOk()->assertSee('Custom terms content.', false);
    }

    public function test_privacy_page_renders_seeded_content(): void
    {
        Page::create(['title' => 'Privacy Policy', 'slug' => Page::PRIVACY, 'content' => '<p>Custom privacy content.</p>']);

        $this->get('/privacy')->assertOk()->assertSee('Custom privacy content.', false);
    }

    public function test_footer_reflects_settings_without_hardcoding(): void
    {
        Setting::set('company_name', 'Totally Custom Bazar Name', 'company');
        Setting::set('phone', '019-CUSTOM-PHONE', 'company');

        $this->get('/')->assertOk()->assertSee('Totally Custom Bazar Name')->assertSee('019-CUSTOM-PHONE');
    }

    public function test_contact_form_stores_a_message_and_shows_confirmation(): void
    {
        Livewire::test(\App\Livewire\Public\ContactForm::class)
            ->set('name', 'Jane Visitor')
            ->set('email', 'jane@example.com')
            ->set('message', 'I would like to know more about your packages.')
            ->call('send')
            ->assertHasNoErrors()
            ->assertSet('sent', true);

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Jane Visitor',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_contact_form_requires_name_email_and_message(): void
    {
        Livewire::test(\App\Livewire\Public\ContactForm::class)
            ->call('send')
            ->assertHasErrors(['name', 'email', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }
}
