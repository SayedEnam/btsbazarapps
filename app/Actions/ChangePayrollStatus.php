<?php

namespace App\Actions;

use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\User;
use App\Services\ActivityLogger;
use RuntimeException;

class ChangePayrollStatus
{
    /**
     * @throws RuntimeException if the transition isn't allowed from the current status.
     */
    public function __invoke(Payroll $payroll, PayrollStatus $newStatus, User $changedBy): Payroll
    {
        if (! $payroll->status->canTransitionTo($newStatus)) {
            throw new RuntimeException(
                "Cannot move a payroll from {$payroll->status->label()} to {$newStatus->label()}."
            );
        }

        $oldStatus = $payroll->status;
        $payroll->status = $newStatus;

        if ($newStatus === PayrollStatus::Approved) {
            $payroll->approved_by = $changedBy->id;
            $payroll->approved_at = now();
        }

        if ($newStatus === PayrollStatus::Paid) {
            $payroll->paid_at = now();
        }

        $payroll->save();

        ActivityLogger::log(
            action: $newStatus->value,
            module: 'Payroll',
            description: "{$changedBy->name} marked payroll for {$payroll->month->format('F Y')} as {$newStatus->label()}",
            model: $payroll,
            oldValues: ['status' => $oldStatus->value],
            newValues: ['status' => $newStatus->value],
        );

        return $payroll->fresh();
    }
}
