<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * One application per listed customer email, covering every status so
     * the admin/officer/customer screens all have something real to show.
     * `officer` mirrors the customer's referral officer where one exists —
     * intentionally, since Application.officer_id is who's *currently
     * handling* the application, independent of (but usually starting as)
     * the permanent referral relationship.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $applications = [
        'sultana.parvin@monthlybazar.test' => ['status' => ApplicationStatus::Approved, 'officer' => 'nusrat.jahan@monthlybazar.test', 'daysAgo' => 20],
        'habibur.rahman@monthlybazar.test' => ['status' => ApplicationStatus::Approved, 'officer' => 'nusrat.jahan@monthlybazar.test', 'daysAgo' => 18],
        'ayesha.siddika@monthlybazar.test' => ['status' => ApplicationStatus::Pending, 'officer' => 'shariful.islam@monthlybazar.test', 'daysAgo' => 1],
        'nazrul.islam@monthlybazar.test' => ['status' => ApplicationStatus::Approved, 'officer' => null, 'daysAgo' => 25],
        'ruma.aktar@monthlybazar.test' => ['status' => ApplicationStatus::UnderReview, 'officer' => 'rina.akter@monthlybazar.test', 'daysAgo' => 3],
        'shahidul.islam@monthlybazar.test' => ['status' => ApplicationStatus::Rejected, 'officer' => null, 'daysAgo' => 15, 'reason' => 'Submitted NID could not be verified.'],
        'moushumi.rahman@monthlybazar.test' => ['status' => ApplicationStatus::Approved, 'officer' => 'jahangir.alam@monthlybazar.test', 'daysAgo' => 10],
        'delwar.hossain@monthlybazar.test' => ['status' => ApplicationStatus::Cancelled, 'officer' => null, 'daysAgo' => 12, 'reason' => 'Customer requested cancellation.'],
        'farida.yasmin@monthlybazar.test' => ['status' => ApplicationStatus::Approved, 'officer' => 'taslima.nasrin@monthlybazar.test', 'daysAgo' => 7],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $package = Package::where('code', 'STD-1000')->first();
        $admin = User::where('email', 'admin@monthlybazar.test')->first();

        if (! $package || ! $admin) {
            return;
        }

        foreach ($this->applications as $customerEmail => $data) {
            $user = User::where('email', $customerEmail)->first();
            $customer = $user ? Customer::where('user_id', $user->id)->first() : null;

            if (! $customer) {
                continue;
            }

            if (Application::where('customer_id', $customer->id)->exists()) {
                continue;
            }

            $officer = $data['officer'] ? User::where('email', $data['officer'])->first() : null;
            $appliedAt = now()->subDays($data['daysAgo']);
            $isTerminal = in_array($data['status'], [ApplicationStatus::Approved, ApplicationStatus::Rejected, ApplicationStatus::Cancelled], true);
            $reviewedAt = $isTerminal ? $appliedAt->copy()->addDays(2) : null;
            $reviewer = $isTerminal ? ($officer ?? $admin) : null;

            $application = Application::create([
                'application_number' => Application::generateApplicationNumber(),
                'customer_id' => $customer->id,
                'officer_id' => $officer?->id,
                'package_id' => $package->id,
                'package_price' => $package->price,
                'application_date' => $appliedAt->toDateString(),
                'status' => $data['status'],
                'reviewed_by' => $reviewer?->id,
                'reviewed_at' => $reviewedAt,
                'rejection_reason' => $data['status'] === ApplicationStatus::Rejected ? ($data['reason'] ?? null) : null,
            ]);

            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'old_status' => null,
                'new_status' => ApplicationStatus::Pending,
                'changed_by' => $user->id,
                'changed_at' => $appliedAt,
            ]);

            if ($data['status'] !== ApplicationStatus::Pending) {
                ApplicationStatusHistory::create([
                    'application_id' => $application->id,
                    'old_status' => ApplicationStatus::Pending,
                    'new_status' => $data['status'],
                    'changed_by' => $reviewer?->id ?? $admin->id,
                    'reason' => $data['reason'] ?? null,
                    'changed_at' => $reviewedAt ?? $appliedAt->copy()->addHours(4),
                ]);
            }

            if ($data['status'] === ApplicationStatus::Approved) {
                $customer->update(['status' => CustomerStatus::Active]);
            }
        }
    }
}
