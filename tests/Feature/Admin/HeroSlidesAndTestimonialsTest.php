<?php

namespace Tests\Feature\Admin;

use App\Enums\HeroSlideDisplayMode;
use App\Enums\HeroSlideStatus;
use App\Enums\TestimonialStatus;
use App\Enums\UserStatus;
use App\Models\HeroSlide;
use App\Models\Role;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HeroSlidesAndTestimonialsTest extends TestCase
{
    use RefreshDatabase;

    protected function asSuperAdmin(): User
    {
        $role = Role::create(['name' => 'Super Admin', 'slug' => Role::SUPER_ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        return $user;
    }

    // --- Hero Slides ---

    public function test_a_hero_slide_can_be_created_edited_and_deleted(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('create')
            ->set('form.title', 'Join today')
            ->set('form.subtitle', 'Fixed pricing, real support.')
            ->set('form.button_text', 'Sign Up')
            ->set('form.button_url', '/register')
            ->call('save')
            ->assertHasNoErrors();

        $slide = HeroSlide::where('title', 'Join today')->first();
        $this->assertNotNull($slide);
        $this->assertSame(HeroSlideStatus::Active, $slide->status);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('edit', $slide->id)
            ->set('form.title', 'Join today — updated')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Join today — updated', $slide->fresh()->title);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('confirmDelete', $slide->id)
            ->call('delete');

        $this->assertSoftDeleted('hero_slides', ['id' => $slide->id]);
    }

    public function test_hero_slide_title_is_required(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('create')
            ->set('form.title', '')
            ->call('save')
            ->assertHasErrors('form.title');
    }

    /**
     * A slide defaults to "text_and_button" and doesn't need an image, but
     * choosing "image_only" makes the image required — a slide with
     * neither text nor an image would render as an empty rectangle.
     */
    public function test_image_only_display_mode_requires_an_image(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('create')
            ->set('form.title', 'Banner slide')
            ->set('form.display_mode', 'image_only')
            ->call('save')
            ->assertHasErrors('form.image');

        $this->assertDatabaseMissing('hero_slides', ['title' => 'Banner slide']);
    }

    public function test_an_image_only_slide_can_be_created_with_an_image(): void
    {
        Storage::fake('public');
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('create')
            ->set('form.title', 'Banner slide')
            ->set('form.display_mode', 'image_only')
            ->set('form.image', UploadedFile::fake()->image('banner.jpg', 1600, 500))
            ->call('save')
            ->assertHasNoErrors();

        $slide = HeroSlide::where('title', 'Banner slide')->first();
        $this->assertNotNull($slide);
        $this->assertSame(HeroSlideDisplayMode::ImageOnly, $slide->display_mode);
        $this->assertFalse($slide->showsText());
        Storage::disk('public')->assertExists($slide->image);
    }

    /**
     * Editing an existing image-only slide without re-uploading a new file
     * must not suddenly demand one — the image already on record satisfies
     * the requirement.
     */
    public function test_editing_an_image_only_slide_without_a_new_upload_does_not_require_one(): void
    {
        Storage::fake('public');
        $admin = $this->asSuperAdmin();
        $slide = HeroSlide::create([
            'title' => 'Existing banner',
            'image' => 'hero-slides/existing.jpg',
            'display_mode' => HeroSlideDisplayMode::ImageOnly,
            'status' => HeroSlideStatus::Active,
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\HeroSlides\Index::class)
            ->call('edit', $slide->id)
            ->set('form.sort_order', 5)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(5, $slide->fresh()->sort_order);
    }

    public function test_hero_slides_index_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.hero-slides.index'))->assertForbidden();
    }

    // --- Testimonials ---

    public function test_a_testimonial_can_be_created_edited_and_deleted(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Testimonials\Index::class)
            ->call('create')
            ->set('form.customer_name', 'Rahim Uddin')
            ->set('form.role_or_company', 'Member since 2025')
            ->set('form.quote', 'Joining was simple and transparent.')
            ->set('form.rating', '5')
            ->call('save')
            ->assertHasNoErrors();

        $testimonial = Testimonial::where('customer_name', 'Rahim Uddin')->first();
        $this->assertNotNull($testimonial);
        $this->assertSame(5, $testimonial->rating);
        $this->assertSame(TestimonialStatus::Active, $testimonial->status);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Testimonials\Index::class)
            ->call('edit', $testimonial->id)
            ->set('form.quote', 'Updated quote text.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Updated quote text.', $testimonial->fresh()->quote);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Testimonials\Index::class)
            ->call('confirmDelete', $testimonial->id)
            ->call('delete');

        $this->assertSoftDeleted('testimonials', ['id' => $testimonial->id]);
    }

    public function test_testimonial_quote_and_customer_name_are_required(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Testimonials\Index::class)
            ->call('create')
            ->set('form.customer_name', '')
            ->set('form.quote', '')
            ->call('save')
            ->assertHasErrors(['form.customer_name', 'form.quote']);
    }

    public function test_an_out_of_range_rating_is_rejected(): void
    {
        $admin = $this->asSuperAdmin();

        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\Testimonials\Index::class)
            ->call('create')
            ->set('form.customer_name', 'Someone')
            ->set('form.quote', 'A quote.')
            ->set('form.rating', '6')
            ->call('save')
            ->assertHasErrors('form.rating');
    }

    public function test_testimonials_index_requires_permission(): void
    {
        $role = Role::create(['name' => 'Admin', 'slug' => Role::ADMIN, 'is_system' => true]);
        $user = User::factory()->create(['status' => UserStatus::Active]);
        $user->roles()->attach($role);

        $this->actingAs($user)->get(route('admin.testimonials.index'))->assertForbidden();
    }

    // --- Public rendering ---

    public function test_active_hero_slides_render_as_a_carousel_on_the_home_page_and_inactive_ones_are_hidden(): void
    {
        HeroSlide::create(['title' => 'Active Slide Title', 'status' => HeroSlideStatus::Active, 'sort_order' => 1]);
        HeroSlide::create(['title' => 'Hidden Slide Title', 'status' => HeroSlideStatus::Inactive, 'sort_order' => 2]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Active Slide Title')
            ->assertDontSee('Hidden Slide Title')
            ->assertSee('id="heroCarousel"', false);
    }

    /**
     * Regression guard: the prev/next controls and indicator dots must sit
     * below the slide content (a `hero-nav-btn`/`hero-indicators` row), not
     * Bootstrap's default absolutely-positioned `.carousel-control-prev`,
     * which visually overlapped the subtitle text at every viewport width
     * — confirmed by screenshotting the rendered page, not something a
     * plain HTML assertion alone would have caught.
     */
    public function test_hero_carousel_controls_sit_below_the_content_not_overlaid_on_it(): void
    {
        HeroSlide::create(['title' => 'Slide One', 'status' => HeroSlideStatus::Active, 'sort_order' => 1]);
        HeroSlide::create(['title' => 'Slide Two', 'status' => HeroSlideStatus::Active, 'sort_order' => 2]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('hero-nav-btn', false)
            ->assertSee('hero-indicators', false)
            ->assertDontSee('carousel-control-prev-icon', false)
            ->assertDontSee('carousel-control-next-icon', false);
    }

    /**
     * An image-only slide must not leak its title/badge/button onto the
     * page — the whole point of the mode is a clean image with no text.
     */
    public function test_an_image_only_hero_slide_hides_its_title_and_button_on_the_home_page(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('banner.jpg')->store('hero-slides', 'public');

        HeroSlide::create([
            'title' => 'Internal Only Title',
            'button_text' => 'Should Not Show',
            'image' => $path,
            'display_mode' => HeroSlideDisplayMode::ImageOnly,
            'status' => HeroSlideStatus::Active,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('id="heroCarousel"', false)
            ->assertSee(basename($path), false)
            ->assertDontSee('Internal Only Title')
            ->assertDontSee('Should Not Show')
            ->assertDontSee('Trusted Membership Platform');
    }

    public function test_a_text_and_button_hero_slide_shows_its_title_and_button_on_the_home_page(): void
    {
        HeroSlide::create([
            'title' => 'Visible Slide Title',
            'button_text' => 'Custom Button',
            'display_mode' => HeroSlideDisplayMode::TextAndButton,
            'status' => HeroSlideStatus::Active,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Visible Slide Title')
            ->assertSee('Custom Button');
    }

    public function test_home_page_falls_back_to_the_default_hero_when_no_slides_exist(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Simple, transparent membership')
            ->assertDontSee('id="heroCarousel"', false);
    }

    public function test_active_testimonials_render_on_the_home_page_and_inactive_ones_are_hidden(): void
    {
        Testimonial::create([
            'customer_name' => 'Visible Customer', 'quote' => 'Great service overall.',
            'status' => TestimonialStatus::Active, 'sort_order' => 1,
        ]);
        Testimonial::create([
            'customer_name' => 'Hidden Customer', 'quote' => 'Should not appear.',
            'status' => TestimonialStatus::Inactive, 'sort_order' => 2,
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Visible Customer')
            ->assertSee('Great service overall.')
            ->assertDontSee('Hidden Customer');
    }

    public function test_home_page_has_no_testimonials_section_when_none_exist(): void
    {
        $response = $this->get('/');

        $response->assertOk()->assertDontSee('id="testimonialCarousel"', false);
    }
}
