<?php

namespace Tests\Feature\Customer;

use App\Enums\CustomerStatus;
use App\Enums\UserStatus;
use App\Livewire\Public\Register;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function seedRoles(): void
    {
        Role::create(['name' => 'Customer', 'slug' => Role::CUSTOMER, 'is_system' => true]);
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);
    }

    public function test_a_visitor_can_register_normally_without_a_referral(): void
    {
        $this->seedRoles();

        Livewire::test(Register::class)
            ->set('form.name', 'Jane Customer')
            ->set('form.email', 'jane@example.com')
            ->set('form.username', 'jane.customer')
            ->set('form.phone', '01711112222')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasNoErrors();

        $user = User::where('email', 'jane@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole(Role::CUSTOMER));
        $this->assertSame(UserStatus::Active, $user->status);
        $this->assertAuthenticatedAs($user);

        $customer = $user->customer;
        $this->assertNotNull($customer);
        $this->assertSame(CustomerStatus::Pending, $customer->status);
        $this->assertSame(0, Referral::count());
    }

    public function test_registering_through_a_valid_referral_link_creates_a_permanent_referral(): void
    {
        $this->seedRoles();

        $officer = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0001']);
        $officer->roles()->attach(Role::where('slug', Role::MARKETING_OFFICER)->first());

        // /r/{code} stores the code in session; simulate that directly since
        // it's the session that the registration form actually reads from.
        session(['referral_code' => '0001']);

        Livewire::test(Register::class)
            ->set('form.name', 'Referred Customer')
            ->set('form.email', 'referred@example.com')
            ->set('form.username', 'referred.customer')
            ->set('form.phone', '01711113333')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasNoErrors();

        $customer = User::where('email', 'referred@example.com')->first()->customer;

        $this->assertNotNull($customer->referral);
        $this->assertSame($officer->id, $customer->referral->officer_id);
        $this->assertSame('0001', $customer->referral->referral_code);

        // The code must not linger in session after it's been consumed.
        $this->assertNull(session('referral_code'));
    }

    /**
     * A customer registering directly at /register (not through a /r/{code}
     * link) can now type an officer's referral code in by hand — added so
     * a direct signup can still be attributed to an officer.
     */
    public function test_a_manually_typed_referral_code_creates_a_permanent_referral(): void
    {
        $this->seedRoles();

        $officer = User::factory()->create(['status' => UserStatus::Active, 'referral_code' => '0002']);
        $officer->roles()->attach(Role::where('slug', Role::MARKETING_OFFICER)->first());

        Livewire::test(Register::class)
            ->set('form.name', 'Typed Code Customer')
            ->set('form.email', 'typedcode@example.com')
            ->set('form.username', 'typed.code.customer')
            ->set('form.phone', '01711119999')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.referral_code', '0002')
            ->call('register')
            ->assertHasNoErrors();

        $customer = User::where('email', 'typedcode@example.com')->first()->customer;

        $this->assertNotNull($customer->referral);
        $this->assertSame($officer->id, $customer->referral->officer_id);
    }

    public function test_a_manually_typed_referral_code_that_does_not_exist_is_rejected(): void
    {
        $this->seedRoles();

        Livewire::test(Register::class)
            ->set('form.name', 'Bad Code Customer')
            ->set('form.email', 'badcode@example.com')
            ->set('form.username', 'bad.code.customer')
            ->set('form.phone', '01711118181')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.referral_code', 'DOES-NOT-EXIST')
            ->call('register')
            ->assertHasErrors('form.referral_code');

        $this->assertSame(0, User::where('email', 'badcode@example.com')->count());
    }

    public function test_an_invalid_referral_code_does_not_block_registration(): void
    {
        $this->seedRoles();
        session(['referral_code' => '9999']);

        Livewire::test(Register::class)
            ->set('form.name', 'No Officer Customer')
            ->set('form.email', 'noofficer@example.com')
            ->set('form.username', 'no.officer.customer')
            ->set('form.phone', '01711114444')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasNoErrors();

        $customer = User::where('email', 'noofficer@example.com')->first()->customer;

        $this->assertNotNull($customer);
        $this->assertNull($customer->referral);
    }

    /**
     * The registration form was trimmed down to just the account fields plus
     * an optional profile photo — father's/mother's name, address, NID,
     * profession, date of birth, and gender are collected later by an
     * admin/officer instead of at signup. This guards that a photo alone
     * (no personal-info fields at all) still produces a full account.
     */
    public function test_registering_with_a_profile_photo_but_no_personal_info_succeeds(): void
    {
        $this->seedRoles();

        Livewire::test(Register::class)
            ->set('form.name', 'Photo Customer')
            ->set('form.email', 'photo@example.com')
            ->set('form.username', 'photo.customer')
            ->set('form.phone', '01711118888')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->set('form.profile_photo', \Illuminate\Http\UploadedFile::fake()->image('avatar.jpg')->size(100))
            ->call('register')
            ->assertHasNoErrors();

        $customer = User::where('email', 'photo@example.com')->first()->customer;

        $this->assertNotNull($customer);
        $this->assertNotNull($customer->profile_photo);
        $this->assertNull($customer->father_name);
        $this->assertNull($customer->nid_number);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $this->seedRoles();
        User::factory()->create(['email' => 'taken@example.com']);

        Livewire::test(Register::class)
            ->set('form.name', 'Someone')
            ->set('form.email', 'taken@example.com')
            ->set('form.username', 'someone.else')
            ->set('form.phone', '01711115555')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors('form.email');

        $this->assertSame(0, Customer::count());
    }

    public function test_duplicate_username_is_rejected(): void
    {
        $this->seedRoles();
        User::factory()->create(['username' => 'takenname']);

        Livewire::test(Register::class)
            ->set('form.name', 'Someone')
            ->set('form.email', 'freshemail@example.com')
            ->set('form.username', 'takenname')
            ->set('form.phone', '01711117777')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors('form.username');

        $this->assertSame(0, Customer::count());
    }

    public function test_password_confirmation_mismatch_is_rejected(): void
    {
        $this->seedRoles();

        Livewire::test(Register::class)
            ->set('form.name', 'Someone')
            ->set('form.email', 'mismatch@example.com')
            ->set('form.username', 'mismatch.someone')
            ->set('form.phone', '01711116666')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'different456')
            ->call('register')
            ->assertHasErrors('form.password');

        $this->assertSame(0, User::where('email', 'mismatch@example.com')->count());
    }

    public function test_the_referral_capture_route_stores_a_valid_code_in_session_and_redirects_to_register(): void
    {
        $this->seedRoles();
        $officer = User::factory()->create(['referral_code' => '0001']);
        $officer->roles()->attach(Role::where('slug', Role::MARKETING_OFFICER)->first());

        $response = $this->get('/r/0001');

        $response->assertRedirect(route('register'));
        $this->assertSame('0001', session('referral_code'));
    }

    public function test_the_referral_capture_route_ignores_an_unknown_code(): void
    {
        $this->seedRoles();

        $response = $this->get('/r/DOES-NOT-EXIST');

        $response->assertRedirect(route('register'));
        $this->assertNull(session('referral_code'));
    }
}
