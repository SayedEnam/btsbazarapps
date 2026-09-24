<?php

namespace App\Livewire\Officer\Applications;

use App\Actions\ChangeApplicationStatus;
use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

#[Layout('layouts.officer')]
#[Title('Applications')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public ?int $rejectingId = null;

    public string $rejectionReason = '';

    public ?int $designationId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Every mutating action below re-fetches the application scoped to
     * Auth::id() (not just findOrFail by the raw ID) — an officer approving
     * or rejecting an application id that isn't actually assigned to them
     * gets a 404, never a silent cross-officer action (spec section 16).
     */
    protected function ownApplicationOrFail(int $applicationId): Application
    {
        return Application::where('officer_id', Auth::id())->findOrFail($applicationId);
    }

    public function markUnderReview(int $applicationId): void
    {
        Gate::authorize('applications.approve');

        $this->applyStatusChange($this->ownApplicationOrFail($applicationId), ApplicationStatus::UnderReview);
    }

    public function approve(int $applicationId): void
    {
        Gate::authorize('applications.approve');

        if ($this->applyStatusChange($this->ownApplicationOrFail($applicationId), ApplicationStatus::Approved)) {
            $this->dispatch('notify', type: 'success', message: 'Application approved — customer is now an active member.');
        }
    }

    public function confirmReject(int $applicationId): void
    {
        $this->ownApplicationOrFail($applicationId);

        $this->rejectingId = $applicationId;
        $this->rejectionReason = '';
    }

    public function reject(): void
    {
        Gate::authorize('applications.reject');

        $this->validate(['rejectionReason' => ['required', 'string', 'max:500']]);

        $application = $this->ownApplicationOrFail($this->rejectingId);

        if ($this->applyStatusChange($application, ApplicationStatus::Rejected, $this->rejectionReason)) {
            $this->rejectingId = null;
            $this->dispatch('notify', type: 'success', message: 'Application rejected.');
        }
    }

    public function updateDesignation(int $applicationId, ?string $designationId): void
    {
        $designationId = $designationId === '' ? null : (int) $designationId;

        $application = $this->ownApplicationOrFail($applicationId);
        $application->update(['designation_id' => $designationId]);
        $this->dispatch('notify', type: 'success', message: 'Designation updated successfully.');
    }

    public function updateRole(int $applicationId, ?string $roleId): void
    {
        if (in_array($roleId, [\App\Models\Role::ADMIN, \App\Models\Role::SUPER_ADMIN], true)) {
            $this->dispatch('notify', type: 'error', message: 'Admin and Superadmin roles are not allowed here.');

            return;
        }

        $application = $this->ownApplicationOrFail($applicationId);
        $user = $application->customer?->user;

        if (! $user) {
            $this->dispatch('notify', type: 'error', message: 'User not found for this application.');

            return;
        }

        if ($roleId) {
            $user->roles()->sync([$roleId]);
        } else {
            $user->roles()->detach();
        }

        $this->dispatch('notify', type: 'success', message: 'Role updated successfully.');
    }

    protected function applyStatusChange(Application $application, ApplicationStatus $status, ?string $reason = null): bool
    {
        try {
            app(ChangeApplicationStatus::class)($application, $status, Auth::user(), $reason);

            return true;
        } catch (RuntimeException $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());

            return false;
        }
    }

    public function render()
    {
        $applications = Application::query()
            ->where('officer_id', Auth::id())
            ->with(['customer.user.roles', 'package', 'designation'])
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('application_number', 'like', "%{$this->search}%")
                    ->orWhereHas('customer.user', function ($uq) {
                        $uq->where('name', 'like', "%{$this->search}%")
                            ->orWhere('phone', 'like', "%{$this->search}%");
                    });
            }))
            ->when($this->statusFilter, fn ($query) => $query->where('status', $this->statusFilter))
            ->latest('application_date')
            ->paginate(10);

        return view('livewire.officer.applications.index', [
            'applications' => $applications,
            'designations' => Designation::orderBy('name')->get(),
            'roles' => \App\Models\Role::query()->whereNotIn('slug', [\App\Models\Role::ADMIN, \App\Models\Role::SUPER_ADMIN])->orderBy('name')->get(),
        ]);
    }
}
