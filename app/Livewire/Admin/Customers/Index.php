<?php

namespace App\Livewire\Admin\Customers;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Customers')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public ?int $viewingId = null;

    public function mount(): void
    {
        Gate::authorize('customers.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function view(int $customerId): void
    {
        $this->viewingId = $customerId;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    /**
     * Manually move a customer between Active/Inactive/Suspended. Most
     * customers reach Active automatically when an application is approved
     * (ChangeApplicationStatus); this is the admin override for the rest —
     * e.g. suspending membership for a policy violation, or reactivating one.
     */
    public function updateStatus(int $customerId, string $status): void
    {
        Gate::authorize('customers.edit');

        $customer = Customer::findOrFail($customerId);
        $newStatus = CustomerStatus::from($status);
        $oldStatus = $customer->status;

        if ($oldStatus === $newStatus) {
            return;
        }

        $customer->update(['status' => $newStatus]);

        ActivityLogger::log(
            action: 'status_changed',
            module: 'Customers',
            description: "{$customer->user->name}'s membership status was changed from {$oldStatus->label()} to {$newStatus->label()}",
            model: $customer,
            oldValues: ['status' => $oldStatus->value],
            newValues: ['status' => $newStatus->value],
        );

        $this->dispatch('notify', type: 'success', message: 'Customer status updated successfully.');
    }

    public function render()
    {
        $customers = Customer::query()
            ->with(['user', 'referral.officer'])
            ->withCount('applications')
            ->when($this->search, fn ($query) => $query->whereHas('user', function ($uq) {
                $uq->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            })->orWhere('nid_number', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.customers.index', [
            'customers' => $customers,
            'viewing' => $this->viewingId
                ? Customer::with(['user', 'referral.officer', 'applications.package'])->find($this->viewingId)
                : null,
            'statuses' => CustomerStatus::cases(),
        ]);
    }
}
