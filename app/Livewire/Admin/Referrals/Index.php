<?php

namespace App\Livewire\Admin\Referrals;

use App\Models\Officer;
use App\Models\Referral;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Referral Management')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $officerFilter = '';

    public function mount(): void
    {
        Gate::authorize('referrals.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingOfficerFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        // Referrals are a permanent record of who first referred a customer
        // (spec section 10) — this screen is a read-only audit/browsing view,
        // never an editor. Reassigning who currently handles an application
        // is a separate, deliberately different concept handled from the
        // Applications screen (see Application.officer_id).
        $referrals = Referral::query()
            ->with(['officer', 'customer.user'])
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('referral_code', 'like', "%{$this->search}%")
                    ->orWhereHas('officer', fn ($oq) => $oq->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('customer.user', fn ($cq) => $cq->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%"));
            }))
            ->when($this->officerFilter, fn ($query) => $query->where('officer_id', $this->officerFilter))
            ->latest('registered_at')
            ->paginate(15);

        return view('livewire.admin.referrals.index', [
            'referrals' => $referrals,
            'officers' => Officer::with('user')->get(),
        ]);
    }
}
