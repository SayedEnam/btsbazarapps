<?php

namespace App\Actions;

use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ApplicationStatusChanged as ApplicationStatusChangedNotification;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ChangeApplicationStatus
{
    /**
     * Move an application to a new status, recording the transition and
     * applying its side effects, all inside one transaction:
     *
     *  - Approved  -> the customer's membership status becomes Active, and
     *                 every Super Admin is notified (spec section 27).
     *  - Rejected  -> the rejection reason is stored; the customer's status
     *                 is left untouched (they may already be Active through
     *                 another approved application — spec section 50).
     *
     * @throws RuntimeException if the transition isn't allowed from the current status.
     */
    public function __invoke(
        Application $application,
        ApplicationStatus $newStatus,
        User $changedBy,
        ?string $reason = null,
    ): Application {
        if (! $application->status->canTransitionTo($newStatus)) {
            throw new RuntimeException(
                "Cannot move an application from {$application->status->label()} to {$newStatus->label()}."
            );
        }

        return DB::transaction(function () use ($application, $newStatus, $changedBy, $reason) {
            $oldStatus = $application->status;

            $application->status = $newStatus;
            $application->reviewed_by = $changedBy->id;
            $application->reviewed_at = now();

            if ($newStatus === ApplicationStatus::Rejected) {
                $application->rejection_reason = $reason;
            }

            $application->save();

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => $changedBy->id,
                'reason' => $reason,
                'changed_at' => now(),
            ]);

            if ($newStatus === ApplicationStatus::Approved) {
                $application->customer?->update(['status' => CustomerStatus::Active]);
            }

            if (in_array($newStatus, [ApplicationStatus::Approved, ApplicationStatus::Rejected], true)) {
                $application->customer?->user?->notify(new ApplicationStatusChangedNotification($application, $newStatus));
            }

            if ($newStatus === ApplicationStatus::Approved) {
                $superAdmins = Role::where('slug', Role::SUPER_ADMIN)->first()?->users ?? collect();

                foreach ($superAdmins as $admin) {
                    $admin->notify(new ApplicationStatusChangedNotification($application, $newStatus));
                }
            }

            ActivityLogger::log(
                action: $newStatus->value,
                module: 'Applications',
                description: "{$changedBy->name} {$this->pastTense($newStatus)} Application #{$application->application_number}".($reason ? " — {$reason}" : ''),
                model: $application,
                oldValues: ['status' => $oldStatus->value],
                newValues: ['status' => $newStatus->value],
            );

            return $application->fresh();
        });
    }

    protected function pastTense(ApplicationStatus $status): string
    {
        return match ($status) {
            ApplicationStatus::Approved => 'approved',
            ApplicationStatus::Rejected => 'rejected',
            ApplicationStatus::Cancelled => 'cancelled',
            ApplicationStatus::UnderReview => 'marked under review',
            ApplicationStatus::Pending => 'reset to pending',
        };
    }
}
