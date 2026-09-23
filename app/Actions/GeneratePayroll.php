<?php

namespace App\Actions;

use App\Enums\PayrollStatus;
use App\Enums\SalaryProfileStatus;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\SalaryProfile;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GeneratePayroll
{
    /**
     * Create (or top up) the payroll batch for a given month: one
     * `PayrollItem` per officer with an active salary profile, snapshotting
     * that profile's figures so a later salary change never rewrites a past
     * month's payroll — the same principle as Application.package_price.
     *
     * Calling this again for a month that already has a payroll only adds
     * items for officers who don't have one yet (an officer added mid-month,
     * for instance) — it never overwrites an existing item, which is what
     * "prevent duplicate payroll... unless explicitly overridden" (spec
     * section 19) means in practice: the unique(payroll_id, officer_id)
     * constraint is the actual enforcement, this method just works with it.
     */
    public function __invoke(Carbon $month, User $generatedBy): Payroll
    {
        return DB::transaction(function () use ($month, $generatedBy) {
            $monthStart = $month->copy()->startOfMonth();

            $payroll = Payroll::firstOrCreate(
                ['month' => $monthStart->toDateString()],
                ['status' => PayrollStatus::Draft, 'generated_by' => $generatedBy->id]
            );

            $existingOfficerIds = $payroll->items()->pluck('officer_id');

            $profiles = SalaryProfile::where('status', SalaryProfileStatus::Active)
                ->whereNotIn('officer_id', $existingOfficerIds)
                ->get()
                ->unique('officer_id');

            foreach ($profiles as $profile) {
                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'officer_id' => $profile->officer_id,
                    'salary_profile_id' => $profile->id,
                    'basic_salary' => $profile->basic_salary,
                    'total_allowance' => $profile->totalAllowance(),
                    'total_deduction' => $profile->deduction,
                    'net_salary' => $profile->netSalary(),
                ]);
            }

            return $payroll->fresh('items');
        });
    }
}
