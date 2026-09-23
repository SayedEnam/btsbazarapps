<?php

namespace App\Livewire\Admin\Officers;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\OfficerForm;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Officer;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Officers')]
class Index extends Component
{
    use WithFileUploads, WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public string $statusFilter = '';

    public string $departmentFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public OfficerForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('officers.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('officers.create');

        $this->editingId = null;
        $this->form->setOfficer(null);
        $this->showModal = true;
    }

    public function edit(int $officerId): void
    {
        Gate::authorize('officers.edit');

        $officer = Officer::with('user')->findOrFail($officerId);

        $this->editingId = $officer->id;
        $this->form->setOfficer($officer);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'officers.edit' : 'officers.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Officer updated successfully.' : 'Officer created successfully.');
    }

    public function confirmDelete(int $officerId): void
    {
        $this->deletingId = $officerId;
    }

    /**
     * Removes the Officer profile only — the underlying User account, role,
     * and referral code are left untouched (managed from the Users screen).
     * This keeps "no longer an active officer" reversible without silently
     * deleting someone's login or orphaning their historical referrals.
     */
    public function delete(): void
    {
        Gate::authorize('officers.delete');

        Officer::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Officer removed successfully.');
    }

    public function render()
    {
        $officers = Officer::query()
            ->with(['user', 'department.parent', 'designation'])
            ->withCount('referrals')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('username', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                })->orWhere('employee_id', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->departmentFilter, function ($query) {
                // Filtering by a parent department also includes officers in
                // its sub-departments — filtering by a sub-department itself
                // still only matches that one.
                $childIds = Department::where('parent_id', $this->departmentFilter)->pluck('id');

                $query->whereIn('department_id', [$this->departmentFilter, ...$childIds]);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.officers.index', [
            'officers' => $officers,
            'departments' => Department::topLevel()->with(['children' => fn ($query) => $query->orderBy('name')])->orderBy('name')->get(),
            'designations' => Designation::orderBy('name')->get(),
        ]);
    }
}
