<?php

namespace App\Livewire\Public;

use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:255')]
    public string $subject = '';

    #[Validate('required|string|max:2000')]
    public string $message = '';

    public bool $sent = false;

    public function send(): void
    {
        $throttleKey = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            throw ValidationException::withMessages([
                'message' => 'Too many messages sent. Please try again in a few minutes.',
            ]);
        }

        $validated = $this->validate();

        RateLimiter::hit($throttleKey, 600);

        ContactMessage::create([
            ...$validated,
            'ip_address' => request()->ip(),
        ]);

        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.public.contact-form');
    }
}
