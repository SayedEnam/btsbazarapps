<?php

namespace App\Livewire\Admin\Designations;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\DesignationForm;
use App\Models\Designation;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Designations')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public DesignationForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('designations.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('designations.create');

        $this->editingId = null;
        $this->form->setDesignation(null);
        $this->showModal = true;
    }

    public function edit(int $designationId): void
    {
        Gate::authorize('designations.edit');

        $designation = Designation::findOrFail($designationId);

        $this->editingId = $designation->id;
        $this->form->setDesignation($designation);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'designations.edit' : 'designations.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Designation updated successfully.' : 'Designation created successfully.');
    }

    public function confirmDelete(int $designationId): void
    {
        $this->deletingId = $designationId;
    }

    public function delete(): void
    {
        Gate::authorize('designations.delete');

        Designation::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Designation deleted successfully.');
    }

    public function render()
    {
        $designations = Designation::query()
            ->withCount('officers')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.designations.index', [
            'designations' => $designations,
        ]);
    }
}
