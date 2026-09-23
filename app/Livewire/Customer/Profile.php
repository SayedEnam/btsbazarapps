<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\UpdatePasswordForm;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.customer')]
#[Title('My Profile')]
class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public string $username = '';

    public ?string $phone = '';

    public string $father_name = '';

    public string $mother_name = '';

    public string $address = '';

    public string $nid_number = '';

    public string $profession = '';

    public string $date_of_birth = '';

    public string $gender = '';

    public $new_profile_photo = null;

    public UpdatePasswordForm $passwordForm;

    public function mount(): void
    {
        $user = Auth::user();
        $customer = $user->customer;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = (string) $user->username;
        $this->phone = $user->phone;

        if ($customer) {
            $this->father_name = (string) $customer->father_name;
            $this->mother_name = (string) $customer->mother_name;
            $this->address = (string) $customer->address;
            $this->nid_number = (string) $customer->nid_number;
            $this->profession = (string) $customer->profession;
            $this->date_of_birth = $customer->date_of_birth?->format('Y-m-d') ?? '';
            $this->gender = (string) $customer->gender;
        }
    }

    public function updateProfile(): void
    {
        $user = Auth::user();
        $customer = $user->customer;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->id)],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'nid_number' => ['nullable', 'string', 'max:30', Rule::unique('customers', 'nid_number')->ignore($customer?->id)],
            'profession' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'new_profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'phone' => $validated['phone'],
        ]);

        $customerData = [
            'father_name' => $validated['father_name'] ?: null,
            'mother_name' => $validated['mother_name'] ?: null,
            'address' => $validated['address'] ?: null,
            'nid_number' => $validated['nid_number'] ?: null,
            'profession' => $validated['profession'] ?: null,
            'date_of_birth' => $validated['date_of_birth'] ?: null,
            'gender' => $validated['gender'] ?: null,
        ];

        if ($this->new_profile_photo) {
            $customerData['profile_photo'] = $this->new_profile_photo->store('customers', 'public');
        }

        if ($customer) {
            $customer->update($customerData);
        } else {
            Customer::create([...$customerData, 'user_id' => $user->id]);
        }

        $this->new_profile_photo = null;

        $this->dispatch('notify', type: 'success', message: 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $this->passwordForm->update();

        $this->dispatch('notify', type: 'success', message: 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.customer.profile');
    }
}
