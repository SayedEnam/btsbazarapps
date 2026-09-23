<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\UserForm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Users')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public string $statusFilter = '';

    public int $perPage = 10;

    public bool $showModal = false;

    public ?int $editingId = null;

    public UserForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('users.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('users.create');

        $this->editingId = null;
        $this->form->setUser(null);
        $this->showModal = true;
    }

    public function edit(int $userId): void
    {
        Gate::authorize('users.edit');

        $user = User::with('roles')->findOrFail($userId);

        $this->editingId = $user->id;
        $this->form->setUser($user);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'users.edit' : 'users.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'User updated successfully.' : 'User created successfully.');
    }

    public function confirmDelete(int $userId): void
    {
        $this->deletingId = $userId;
    }

    public function delete(): void
    {
        $user = User::findOrFail($this->deletingId);

        Gate::authorize('delete', $user);

        $user->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'User deleted successfully.');
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('username', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
