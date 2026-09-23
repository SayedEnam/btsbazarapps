<?php

namespace App\Livewire\Customer\Applications;

use App\Actions\SubmitApplication;
use App\Enums\ApplicationStatus;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use RuntimeException;

#[Layout('layouts.customer')]
#[Title('Apply for a Package')]
class Create extends Component
{
    public string $notes = '';

    /**
     * Applying while an application is already Pending/Under Review is
     * blocked at the point of submission too (SubmitApplication guards it
     * with a row lock), but checking it here as well means the customer
     * sees why up front instead of only after clicking Apply.
     */
    public function mount(): void
    {
        $customer = Auth::user()->customer;

        $hasOpenApplication = $customer?->applications()
            ->whereIn('status', [ApplicationStatus::Pending, ApplicationStatus::UnderReview])
            ->exists();

        if ($hasOpenApplication) {
            $this->redirectRoute('customer.dashboard', navigate: false);
        }
    }

    public function apply(int $packageId): void
    {
        $customer = Auth::user()->customer;
        $package = Package::active()->findOrFail($packageId);

        try {
            app(SubmitApplication::class)($customer, $package, $this->notes ?: null);
        } catch (RuntimeException $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());

            return;
        }

        session()->flash('status', 'Your application has been submitted and is now pending review.');
        $this->redirectRoute('customer.dashboard', navigate: false);
    }

    public function render()
    {
        return view('livewire.customer.applications.create', [
            'packages' => Package::active()->ordered()->get(),
        ]);
    }
}
