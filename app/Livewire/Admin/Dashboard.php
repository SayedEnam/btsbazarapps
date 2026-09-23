<?php

namespace App\Livewire\Admin;

use App\Enums\ApplicationStatus;
use App\Enums\CustomerStatus;
use App\Enums\OfficerStatus;
use App\Models\Application;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Officer;
use App\Models\SalaryPayment;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        // Every revenue/expense figure here is computed directly from the
        // real record tables — never from a synthesized "income" transaction
        // — matching the Accounts dashboard's spec section 23 rule.
        $totalPackageValue = Application::where('status', ApplicationStatus::Approved)->sum('package_price');
        $totalExpense = bcadd((string) SalaryPayment::sum('amount'), (string) Expense::sum('amount'), 2);

        return view('livewire.admin.dashboard', [
            'userCount' => User::count(),
            'activeCustomerCount' => Customer::where('status', CustomerStatus::Active)->count(),
            'pendingApplicationCount' => Application::where('status', ApplicationStatus::Pending)->count(),
            'activeOfficerCount' => Officer::where('status', OfficerStatus::Active)->count(),
            'totalRevenue' => $totalPackageValue,
            'totalExpense' => $totalExpense,
            'netBalance' => bcsub((string) $totalPackageValue, $totalExpense, 2),
            'applicationsThisMonth' => Application::whereMonth('application_date', now()->month)
                ->whereYear('application_date', now()->year)
                ->count(),
            'pendingApplications' => Application::with(['customer.user', 'package'])
                ->where('status', ApplicationStatus::Pending)
                ->latest('application_date')
                ->take(5)
                ->get(),
            'recentActivity' => ActivityLog::with('user')->latest()->take(6)->get(),
        ]);
    }
}
