<?php

namespace App\Livewire\Admin\Applications;

use App\Actions\ChangeApplicationStatus;
use App\Enums\ApplicationStatus;
use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Models\Application;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Applications')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public string $statusFilter = '';

    public string $packageFilter = '';

    public string $officerFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public ?int $viewingId = null;

    public ?int $rejectingId = null;

    public string $rejectionReason = '';

    public ?int $cancellingId = null;

    public string $cancellationReason = '';

    public ?int $reassigningId = null;

    public string $newOfficerId = '';

    public function mount(): void
    {
        Gate::authorize('applications.view');
    }

    public function rules(): array
    {
        return [
            'rejectionReason' => ['required', 'string', 'max:500'],
            'newOfficerId' => ['nullable', 'exists:users,id'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPackageFilter(): void
    {
        $this->resetPage();
    }

    public function updatingOfficerFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function view(int $applicationId): void
    {
        $this->viewingId = $applicationId;
    }

    public function markUnderReview(int $applicationId): void
    {
        Gate::authorize('applications.approve');

        $application = Application::findOrFail($applicationId);

        if (! $this->applyStatusChange($application, ApplicationStatus::UnderReview)) {
            return;
        }

        $this->dispatch('notify', type: 'success', message: 'Application marked as under review.');
    }

    public function approve(int $applicationId): void
    {
        Gate::authorize('applications.approve');

        $application = Application::findOrFail($applicationId);

        if (! $this->applyStatusChange($application, ApplicationStatus::Approved)) {
            return;
        }

        $this->dispatch('notify', type: 'success', message: 'Application approved — customer is now an active member.');
    }

    public function confirmReject(int $applicationId): void
    {
        $this->rejectingId = $applicationId;
        $this->rejectionReason = '';
    }

    public function reject(): void
    {
        Gate::authorize('applications.reject');

        $this->validateOnly('rejectionReason');

        $application = Application::findOrFail($this->rejectingId);

        if (! $this->applyStatusChange($application, ApplicationStatus::Rejected, $this->rejectionReason)) {
            return;
        }

        $this->rejectingId = null;
        $this->dispatch('notify', type: 'success', message: 'Application rejected.');
    }

    public function confirmCancel(int $applicationId): void
    {
        $this->cancellingId = $applicationId;
        $this->cancellationReason = '';
    }

    public function cancel(): void
    {
        Gate::authorize('applications.cancel');

        $application = Application::findOrFail($this->cancellingId);

        if (! $this->applyStatusChange($application, ApplicationStatus::Cancelled, $this->cancellationReason ?: null)) {
            return;
        }

        $this->cancellingId = null;
        $this->dispatch('notify', type: 'success', message: 'Application cancelled.');
    }

    /**
     * Applies a status transition, surfacing an invalid-transition attempt
     * (e.g. a stale UI after someone else already acted on the same
     * application) as a toast instead of a 500 error.
     */
    protected function applyStatusChange(Application $application, ApplicationStatus $status, ?string $reason = null): bool
    {
        try {
            app(ChangeApplicationStatus::class)($application, $status, Auth::user(), $reason);

            return true;
        } catch (\RuntimeException $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());

            return false;
        }
    }

    public function confirmReassign(int $applicationId): void
    {
        Gate::authorize('applications.reassign');

        $application = Application::findOrFail($applicationId);

        $this->reassigningId = $applicationId;
        $this->newOfficerId = (string) $application->officer_id;
    }

    /**
     * Reassigns who is currently handling the application — independent of
     * the customer's permanent referral relationship in `referrals`, which
     * is never touched here (spec section 10 vs section 13).
     */
    public function reassign(): void
    {
        Gate::authorize('applications.reassign');

        $this->validateOnly('newOfficerId');

        $application = Application::findOrFail($this->reassigningId);
        $application->update(['officer_id' => $this->newOfficerId ?: null]);

        $this->reassigningId = null;
        $this->dispatch('notify', type: 'success', message: 'Officer reassigned successfully.');
    }

    public function render()
    {
        $applications = Application::query()
            ->with(['customer.user', 'package', 'officer'])
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('application_number', 'like', "%{$this->search}%")
                    ->orWhereHas('customer.user', function ($uq) {
                        $uq->where('name', 'like', "%{$this->search}%")
                            ->orWhere('phone', 'like', "%{$this->search}%");
                    });
            }))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->packageFilter, fn ($query) => $query->where('package_id', $this->packageFilter))
            ->when($this->officerFilter, fn ($query) => $query->where('officer_id', $this->officerFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('application_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('application_date', '<=', $this->dateTo))
            ->latest('application_date')
            ->paginate(10);

        return view('livewire.admin.applications.index', [
            'applications' => $applications,
            'packages' => Package::orderBy('name')->get(),
            'officers' => User::whereHas('roles', fn ($q) => $q->where('slug', Role::MARKETING_OFFICER))->orderBy('name')->get(),
            'viewing' => $this->viewingId ? Application::with(['customer.user', 'package', 'officer', 'reviewedBy', 'statusHistories.changedBy'])->find($this->viewingId) : null,
        ]);
    }
}
