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
        $referrals = Referral::query()
            ->where('officer_id', Auth::id())
            ->with(['customer.user', 'customer.applications.designation', 'customer.applications.package', 'officer.officer.designation'])
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->whereHas('customer', function ($q) {
                $q->where('status', $this->statusFilter);
            }))
            ->latest('registered_at')
            ->paginate(10);

        return view('livewire.officer.referrals.all-index', [
            'referrals' => $referrals,
            'totalReferrals' => Referral::where('officer_id', Auth::id())->count(),
        ]);
    }
}
