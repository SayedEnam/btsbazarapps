<?php

namespace App\Livewire\Officer;

use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.officer')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function markNotificationRead(string $notificationId): void
    {
        Auth::user()->notifications()->where('id', $notificationId)->first()?->markAsRead();
    }

    public function render()
    {
        // Always scoped to the logged-in officer — there is no ID parameter
        // anywhere in this component for someone to tamper with (spec
        // section 16's "no cross-officer access" requirement).
        $referrals = Auth::user()->referredCustomers()->with('customer')->get();

        $statusCounts = $referrals
            ->pluck('customer.status')
            ->filter()
            ->countBy(fn (CustomerStatus $status) => $status->value);

        $applications = Application::where('officer_id', Auth::id())->get();
        $applicationCounts = $applications->countBy(fn (Application $app) => $app->status->value);

        return view('livewire.officer.dashboard', [
            'totalReferrals' => $referrals->count(),
            'activeMembers' => $statusCounts->get(CustomerStatus::Active->value, 0),
            'pendingMembers' => $statusCounts->get(CustomerStatus::Pending->value, 0),
            'inactiveOrSuspended' => $statusCounts->get(CustomerStatus::Inactive->value, 0) + $statusCounts->get(CustomerStatus::Suspended->value, 0),
            'chartLabels' => ['Active', 'Pending', 'Inactive', 'Suspended'],
            'chartData' => [
                $statusCounts->get(CustomerStatus::Active->value, 0),
                $statusCounts->get(CustomerStatus::Pending->value, 0),
                $statusCounts->get(CustomerStatus::Inactive->value, 0),
                $statusCounts->get(CustomerStatus::Suspended->value, 0),
            ],
            'pendingApplications' => $applicationCounts->get(ApplicationStatus::Pending->value, 0) + $applicationCounts->get(ApplicationStatus::UnderReview->value, 0),
            'approvedApplications' => $applicationCounts->get(ApplicationStatus::Approved->value, 0),
            'rejectedApplications' => $applicationCounts->get(ApplicationStatus::Rejected->value, 0),
            'totalPackageValue' => $applications->where('status', ApplicationStatus::Approved)->sum('package_price'),
            'notifications' => Auth::user()->unreadNotifications()->latest()->take(5)->get(),
        ]);
    }
}
