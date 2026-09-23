<?php

namespace App\Livewire\Admin\SalaryProfiles;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\SalaryProfileForm;
use App\Models\Officer;
use App\Models\SalaryProfile;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Salary Profiles')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public SalaryProfileForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('salary-profiles.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('salary-profiles.create');

        $this->editingId = null;
        $this->form->setProfile(null);
        $this->showModal = true;
    }

    public function edit(int $profileId): void
    {
        Gate::authorize('salary-profiles.edit');

        $profile = SalaryProfile::findOrFail($profileId);

        $this->editingId = $profile->id;
        $this->form->setProfile($profile);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'salary-profiles.edit' : 'salary-profiles.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Salary profile updated successfully.' : 'Salary profile created successfully.');
    }

    public function confirmDelete(int $profileId): void
    {
        $this->deletingId = $profileId;
    }

    public function delete(): void
    {
        Gate::authorize('salary-profiles.delete');

        SalaryProfile::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Salary profile deleted successfully.');
    }

    public function render()
    {
        $profiles = SalaryProfile::query()
            ->with('officer')
            ->when($this->search, fn ($query) => $query->whereHas('officer', function ($q) {
                $q->where('name', 'like', "%{$this->search}%");
            }))
            ->latest('effective_from')
            ->paginate(10);

        return view('livewire.admin.salary-profiles.index', [
            'profiles' => $profiles,
            'officers' => Officer::with('user')->get(),
        ]);
    }
}
