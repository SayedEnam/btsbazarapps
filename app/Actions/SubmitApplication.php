<?php

namespace App\Actions;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use App\Notifications\NewApplicationSubmitted;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SubmitApplication
{
    /**
     * Create a new application for the given customer/package, snapshotting
     * the package's current price so a later price change never rewrites
     * this application's history (spec section 11).
     *
     * @throws RuntimeException if the customer already has an application awaiting review.
     */
    public function __invoke(Customer $customer, Package $package, ?string $notes = null): Application
    {
        return DB::transaction(function () use ($customer, $package, $notes) {
            $hasOpenApplication = $customer->applications()
                ->whereIn('status', [ApplicationStatus::Pending, ApplicationStatus::UnderReview])
                ->lockForUpdate()
                ->exists();

            if ($hasOpenApplication) {
                throw new RuntimeException('You already have an application awaiting review.');
            }

            $officerId = $customer->referral?->officer_id;

            $application = Application::create([
                'application_number' => Application::generateApplicationNumber(),
                'customer_id' => $customer->id,
                'officer_id' => $officerId,
                'package_id' => $package->id,
                'package_price' => $package->price,
                'application_date' => now()->toDateString(),
                'status' => ApplicationStatus::Pending,
                'notes' => $notes,
            ]);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'old_status' => null,
                'new_status' => ApplicationStatus::Pending,
                'changed_by' => $customer->user_id,
                'changed_at' => now(),
            ]);

            if ($officerId) {
                User::find($officerId)?->notify(new NewApplicationSubmitted($application));
            }

            return $application;
        });
    }
}
