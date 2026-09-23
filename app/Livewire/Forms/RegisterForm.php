<?php

namespace App\Livewire\Forms;

use App\Enums\CustomerStatus;
use App\Enums\UserStatus;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class RegisterForm extends Form
{
    use WithFileUploads;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|string|email|max:255|unique:users,email')]
    public string $email = '';

    #[Validate('required|string|max:50|regex:/^[a-zA-Z0-9._-]+$/|unique:users,username')]
    public string $username = '';

    #[Validate('required|string|max:20|unique:users,phone')]
    public string $phone = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[Validate('nullable|string|max:20|exists:users,referral_code')]
    public string $referral_code = '';

    #[Validate('nullable|image|max:2048')]
    public $profile_photo = null;

    /**
     * Register the account, create the customer profile, and — if a valid
     * referral code was captured — record the permanent referral
     * relationship. Every write happens in one transaction: a half-created
     * account with no customer profile (or a customer with no user) must
     * never be possible.
     *
     * $lockedReferralCode is the code captured in session by a /r/{code}
     * link, if any — it always wins over whatever is typed in the form
     * field, since it was already verified valid when the link was
     * followed (see ReferralController::capture).
     */
    public function register(?string $lockedReferralCode): User
    {
        $throttleKey = 'register:'.request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'form.email' => 'Too many registration attempts. Please try again in a few minutes.',
            ]);
        }

        $this->validate();

        RateLimiter::hit($throttleKey, 600);

        $referralCode = $lockedReferralCode ?: ($this->referral_code ?: null);

        return DB::transaction(function () use ($referralCode) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'status' => UserStatus::Active,
                'email_verified_at' => now(),
            ]);

            $customerRole = Role::where('slug', Role::CUSTOMER)->firstOrFail();
            $user->roles()->attach($customerRole);

            $photoPath = $this->profile_photo?->store('customers', 'public');

            $customer = Customer::create([
                'user_id' => $user->id,
                'profile_photo' => $photoPath,
                'status' => CustomerStatus::Pending,
            ]);

            if ($referralCode) {
                $officer = User::where('referral_code', $referralCode)->first();

                if ($officer) {
                    Referral::create([
                        'officer_id' => $officer->id,
                        'customer_id' => $customer->id,
                        'referral_code' => $referralCode,
                        'registered_at' => now(),
                    ]);
                }
            }

            return $user;
        });
    }
}
