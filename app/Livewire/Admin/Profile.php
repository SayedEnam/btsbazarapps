<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\UpdatePasswordForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('My Profile')]
class Profile extends Component
{
    use ValidatesOnUpdate;

    public string $name = '';

    public string $email = '';

    public string $username = '';

    public ?string $phone = '';

    public UpdatePasswordForm $passwordForm;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = (string) $user->username;
        $this->phone = $user->phone;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
        ];
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        Auth::user()->update($validated);

        $this->dispatch('notify', type: 'success', message: 'Profile updated successfully.');
    }

    public function updatePassword(): void
    {
        $this->passwordForm->update();

        $this->dispatch('notify', type: 'success', message: 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile');
    }
}
