<?php

namespace Tests\Feature\Security;

use App\Livewire\Public\ContactForm;
use App\Livewire\Public\Register;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_contact_form_blocks_submissions_after_three_within_the_window(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            Livewire::test(ContactForm::class)
                ->set('name', "Visitor {$i}")
                ->set('email', "visitor{$i}@example.com")
                ->set('message', 'Hello, I have a question about membership.')
                ->call('send')
                ->assertHasNoErrors();
        }

        Livewire::test(ContactForm::class)
            ->set('name', 'Visitor 4')
            ->set('email', 'visitor4@example.com')
            ->set('message', 'One more message that should be blocked.')
            ->call('send')
            ->assertHasErrors('message');

        $this->assertSame(3, \App\Models\ContactMessage::count());
    }

    public function test_registration_blocks_attempts_after_five_within_the_window(): void
    {
        Role::create(['name' => 'Customer', 'slug' => Role::CUSTOMER, 'is_system' => true]);
        Role::create(['name' => 'Marketing Officer', 'slug' => Role::MARKETING_OFFICER, 'is_system' => true]);

        for ($i = 1; $i <= 5; $i++) {
            Livewire::test(Register::class)
                ->set('form.name', "Applicant {$i}")
                ->set('form.email', "applicant{$i}@example.com")
                ->set('form.username', "applicant{$i}")
                ->set('form.phone', "0171000000{$i}")
                ->set('form.password', 'password123')
                ->set('form.password_confirmation', 'password123')
                ->call('register')
                ->assertHasNoErrors();
        }

        Livewire::test(Register::class)
            ->set('form.name', 'Applicant Six')
            ->set('form.email', 'applicant6@example.com')
            ->set('form.username', 'applicant6')
            ->set('form.phone', '01710000006')
            ->set('form.password', 'password123')
            ->set('form.password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors('form.email');

        $this->assertSame(5, \App\Models\User::where('email', 'like', 'applicant%@example.com')->count());
    }
}
