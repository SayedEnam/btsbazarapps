<?php

namespace App\Livewire\Forms;

use App\Enums\UserStatus;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UserForm extends Form
{
    // Validation lives solely in rules() below — see the note in RoleForm
    // for why property-level #[Validate] attributes are deliberately absent.
    public ?User $editing = null;

    public string $name = '';

    public string $email = '';

    public string $username = '';

    public ?string $phone = '';

    public string $password = '';

    public string $status = 'active';

    /** @var array<int, int> */
    public array $role_ids = [];

    public function setUser(?User $user): void
    {
        $this->editing = $user;

        if ($user) {
            $this->name = $user->name;
            $this->email = $user->email;
            $this->username = (string) $user->username;
            $this->phone = $user->phone;
            $this->status = $user->status->value;
            $this->role_ids = $user->roles->pluck('id')->all();
        } else {
            $this->reset(['name', 'email', 'username', 'phone', 'password', 'status', 'role_ids']);
            $this->status = UserStatus::Active->value;
        }
    }

    public function rules(): array
    {
        $userId = $this->editing?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => [$this->editing ? 'nullable' : 'required', 'string', 'min:8'],
            'status' => ['required', Rule::in(array_map(fn ($case) => $case->value, UserStatus::cases()))],
            'role_ids' => ['array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    public function save(): User
    {
        $this->validate();

        $isNew = ! $this->editing;
        $oldStatus = $this->editing?->status->value;

        $attributes = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone ?: null,
            'status' => $this->status,
        ];

        if ($this->password !== '') {
            $attributes['password'] = Hash::make($this->password);
        }

        $user = $this->editing ?? new User;
        $user->fill($attributes);
        $user->save();

        $user->roles()->sync($this->role_ids);

        ActivityLogger::log(
            action: $isNew ? 'created' : 'updated',
            module: 'Users',
            description: Auth::user()->name.' '.($isNew ? 'created user' : 'updated user')." {$user->name}",
            model: $user,
            oldValues: $isNew ? null : ['status' => $oldStatus],
            newValues: ['status' => $user->status->value],
        );

        return $user;
    }
}
