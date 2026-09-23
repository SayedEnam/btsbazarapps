<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->form->validate();
        $this->form->authenticate();

        Session::regenerate();

        $user = Auth::user();

        $default = match (true) {
            $user->hasRole(Role::CUSTOMER) => route('customer.dashboard', absolute: false),
            $user->hasRole(Role::MARKETING_OFFICER) => route('officer.dashboard', absolute: false),
            default => route('admin.dashboard', absolute: false),
        };

        $this->redirectIntended(default: $default, navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
