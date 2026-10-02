<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.officer')]
#[Title('All Referrals')]
class AllIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $officerFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingOfficerFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $referrals = Referral::query()
            ->with(['customer.user', 'officer'])
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->whereHas('customer', function ($q) {
                $q->where('status', $this->statusFilter);
            }))
            ->when($this->officerFilter, fn ($query) => $query->where('officer_id', $this->officerFilter))
            ->latest('registered_at')
            ->paginate(10);

        $officers = \App\Models\User::whereHas('roles', function ($q) {
            $q->where('slug', \App\Models\Role::MARKETING_OFFICER);
        })->orderBy('name')->get();

        return view('livewire.officer.referrals.all-index', [
            'referrals' => $referrals,
            'officers' => $officers,
        ]);
    }
}
