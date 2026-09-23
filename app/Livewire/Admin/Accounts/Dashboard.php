<?php

namespace App\Livewire\Admin\Accounts;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Expense;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Accounts')]
class Dashboard extends Component
{
    public function mount(): void
    {
        Gate::authorize('accounts.view');
    }

    public function render()
    {
        // Every figure here is computed directly from the real record tables
        // (Applications/SalaryPayments/Expenses) — never from a synthesized
        // "income" transaction, per spec section 23's explicit instruction
        // not to fake income just because an application has a package value.
        $totalPackageValue = Application::where('status', ApplicationStatus::Approved)->sum('package_price');
        $totalSalaryPaid = SalaryPayment::sum('amount');
        $totalOtherExpense = Expense::sum('amount');
        $totalExpense = bcadd((string) $totalSalaryPaid, (string) $totalOtherExpense, 2);
        $netBalance = bcsub((string) $totalPackageValue, $totalExpense, 2);

        return view('livewire.admin.accounts.dashboard', [
            'totalPackageValue' => $totalPackageValue,
            'totalSalaryPaid' => $totalSalaryPaid,
            'totalOtherExpense' => $totalOtherExpense,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
        ]);
    }
}
