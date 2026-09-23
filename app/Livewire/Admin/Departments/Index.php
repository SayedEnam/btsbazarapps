<?php

namespace App\Livewire\Admin\Departments;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\DepartmentForm;
use App\Models\Department;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Departments')]
class Index extends Component
{
    use ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public DepartmentForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('departments.view');
    }

    public function create(): void
    {
        Gate::authorize('departments.create');

        $this->editingId = null;
        $this->form->setDepartment(null);
        $this->showModal = true;
    }

    public function createSubDepartment(int $parentId): void
    {
        Gate::authorize('departments.create');

        $this->editingId = null;
        $this->form->setDepartment(null);
        $this->form->parent_id = $parentId;
        $this->showModal = true;
    }

    public function edit(int $departmentId): void
    {
        Gate::authorize('departments.edit');

        $department = Department::findOrFail($departmentId);

        $this->editingId = $department->id;
        $this->form->setDepartment($department);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'departments.edit' : 'departments.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Department updated successfully.' : 'Department created successfully.');
    }

    public function confirmDelete(int $departmentId): void
    {
        $this->deletingId = $departmentId;
    }

    /**
     * Deleting a parent department never deletes its sub-departments — the
     * `parent_id` foreign key is nullOnDelete, so any sub-department just
     * becomes top-level again, matching how Officer/Package deletions never
     * cascade-destroy related records elsewhere in this app.
     */
    public function delete(): void
    {
        Gate::authorize('departments.delete');

        Department::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Department deleted successfully.');
    }

    /**
     * No pagination here on purpose — a company's department list is
     * realistically a handful of entries, and paginating would split a
     * parent from its own sub-departments across pages, breaking the tree
     * display for no real benefit at this scale.
     */
    public function render()
    {
        if ($this->search !== '') {
            $departments = Department::query()
                ->with('parent')
                ->withCount('officers')
                ->where('name', 'like', "%{$this->search}%")
                ->orderBy('name')
                ->get();
        } else {
            $departments = Department::query()
                ->topLevel()
                ->with(['children' => fn ($query) => $query->withCount('officers')->orderBy('name')])
                ->withCount('officers')
                ->orderBy('name')
                ->get();
        }

        return view('livewire.admin.departments.index', [
            'departments' => $departments,
            'searching' => $this->search !== '',
            'parentOptions' => Department::topLevel()->orderBy('name')->get(),
        ]);
    }
}
