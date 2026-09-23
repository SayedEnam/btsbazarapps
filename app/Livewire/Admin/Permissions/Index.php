<?php

namespace App\Livewire\Admin\Permissions;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\PermissionForm;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Permissions')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public PermissionForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('permissions.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('permissions.create');

        $this->editingId = null;
        $this->form->setPermission(null);
        $this->showModal = true;
    }

    public function edit(int $permissionId): void
    {
        Gate::authorize('permissions.edit');

        $permission = Permission::findOrFail($permissionId);

        $this->editingId = $permission->id;
        $this->form->setPermission($permission);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'permissions.edit' : 'permissions.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Permission updated successfully.' : 'Permission created successfully.');
    }

    public function confirmDelete(int $permissionId): void
    {
        $this->deletingId = $permissionId;
    }

    public function delete(): void
    {
        Gate::authorize('permissions.delete');

        Permission::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Permission deleted successfully.');
    }

    public function render()
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%"))
            ->orderBy('group')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.permissions.index', [
            'permissions' => $permissions,
        ]);
    }
}
