<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.officer')]
#[Title('My Referrals')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Hard-scoped to Auth::id() with no ID parameter anywhere in this
        // component — an officer cannot see another officer's referrals by
        // tampering with a route or Livewire request, because there is
        // nothing here to tamper with (spec section 16).
        $referrals = Referral::query()
            ->where('officer_id', Auth::id())
            ->with('customer.user')
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->whereHas('customer', function ($q) {
                $q->where('status', $this->statusFilter);
            }))
            ->latest('registered_at')
            ->paginate(10);

        return view('livewire.officer.referrals.index', [
            'referrals' => $referrals,
        ]);
    }
}
