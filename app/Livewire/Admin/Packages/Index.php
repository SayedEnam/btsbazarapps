<?php

namespace App\Livewire\Admin\Packages;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\PackageForm;
use App\Models\Package;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Packages')]
class Index extends Component
{
    use WithFileUploads, WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public string $statusFilter = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public PackageForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('packages.view');
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
        Gate::authorize('packages.create');

        $this->editingId = null;
        $this->form->setPackage(null);
        $this->showModal = true;
    }

    public function edit(int $packageId): void
    {
        Gate::authorize('packages.edit');

        $package = Package::findOrFail($packageId);

        $this->editingId = $package->id;
        $this->form->setPackage($package);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'packages.edit' : 'packages.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Package updated successfully.' : 'Package created successfully.');
    }

    public function confirmDelete(int $packageId): void
    {
        $this->deletingId = $packageId;
    }

    /**
     * Soft-deletes only — a package that already has applications carries
     * financial history that must never disappear (spec section 53).
     */
    public function delete(): void
    {
        Gate::authorize('packages.delete');

        Package::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Package deleted successfully.');
    }

    public function render()
    {
        $packages = Package::query()
            ->withCount('applications')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->ordered()
            ->paginate(10);

        return view('livewire.admin.packages.index', [
            'packages' => $packages,
        ]);
    }
}
