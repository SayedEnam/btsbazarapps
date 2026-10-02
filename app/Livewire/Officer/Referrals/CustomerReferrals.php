<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Customer;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.officer')]
#[Title('Customer Referrals')]
class CustomerReferrals extends Component
{
    use WithPagination;

    public ?Customer $customer = null;

    public string $search = '';

    public function mount(int $customerId): void
    {
        $this->customer = Customer::where('id', $customerId)
            ->whereHas('referral', function ($q) {
                $q->where('officer_id', Auth::id());
            })
            ->firstOrFail();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $referrals = Referral::query()
            ->where('officer_id', $this->customer->referral->officer_id)
            ->where('referral_code', $this->customer->user->referral_code)
            ->with(['customer.user', 'customer.applications.designation'])
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->latest('registered_at')
            ->paginate(10);

        return view('livewire.officer.referrals.customer-referrals', [
            'customer' => $this->customer,
            'referrals' => $referrals,
        ]);
    }
}
