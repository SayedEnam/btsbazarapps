<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Customer;
use App\Models\Referral;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.officer')]
#[Title('Customer Details')]
class CustomerDetail extends Component
{
    public ?Customer $customer = null;

    public ?Referral $referral = null;

    public function mount(int $customerId): void
    {
        $referral = Referral::where('officer_id', Auth::id())
            ->where('customer_id', $customerId)
            ->with(['customer.user', 'customer.applications.designation', 'customer.applications.package'])
            ->firstOrFail();

        $this->customer = $referral->customer;
        $this->referral = $referral;
    }

    public function render()
    {
        $latestApplication = $this->customer?->applications->sortByDesc('application_date')->first();

        return view('livewire.officer.referrals.customer-detail', [
            'latestApplication' => $latestApplication,
        ]);
    }
}
