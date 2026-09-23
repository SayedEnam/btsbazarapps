<?php

namespace App\Livewire\Admin\Roles;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\RoleForm;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Roles')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public RoleForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('roles.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('roles.create');

        $this->editingId = null;
        $this->form->setRole(null);
        $this->showModal = true;
    }

    public function edit(int $roleId): void
    {
        Gate::authorize('roles.edit');

        $role = Role::with('permissions')->findOrFail($roleId);

        $this->editingId = $role->id;
        $this->form->setRole($role);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'roles.edit' : 'roles.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Role updated successfully.' : 'Role created successfully.');
    }

    public function confirmDelete(int $roleId): void
    {
        $this->deletingId = $roleId;
    }

    public function delete(): void
    {
        Gate::authorize('roles.delete');

        $role = Role::findOrFail($this->deletingId);

        if ($role->is_system) {
            $this->deletingId = null;
            $this->dispatch('notify', type: 'error', message: 'System roles cannot be deleted.');

            return;
        }

        $role->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Role deleted successfully.');
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount('users')
            ->with('permissions')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
            'permissions' => Permission::orderBy('group')->orderBy('name')->get()->groupBy('group'),
        ]);
    }
}
