<?php

namespace App\Livewire\Public;

use App\Livewire\Forms\RegisterForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.guest', ['wide' => true])]
#[Title('Create Your Account')]
class Register extends Component
{
    use WithFileUploads;

    public RegisterForm $form;

    /**
     * The referring officer, resolved once at mount from the referral code
     * captured in session by the /r/{code} route — not from the URL, which
     * the spec explicitly says registration must not depend on remaining
     * available.
     */
    public ?string $referringOfficerName = null;

    /**
     * True when the referral code came from a /r/{code} link (already
     * verified valid) rather than being typed in by hand — the field is
     * shown locked/read-only in that case so it can't be accidentally
     * cleared, losing the officer's credit for the signup.
     */
    public bool $referralCodeLocked = false;

    public function mount(): void
    {
        if ($code = Session::get('referral_code')) {
            $officer = User::where('referral_code', $code)->first();

            if ($officer) {
                $this->referringOfficerName = $officer->name;
                $this->form->referral_code = $code;
                $this->referralCodeLocked = true;
            }
        }
    }

    public function register(): void
    {
        $lockedReferralCode = Session::get('referral_code');

        $user = $this->form->register($lockedReferralCode);

        Session::forget('referral_code');

        Auth::login($user);
        Session::regenerate();

        $this->redirectRoute('customer.dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.public.register');
    }
}
