<?php

namespace App\Livewire\Admin\Reports;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Officer;
use App\Models\Package;
use App\Models\PayrollItem;
use App\Models\Referral;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
#[Title('Reports')]
class Index extends Component
{
    use WithPagination;

    public string $reportType = 'customers';

    public string $search = '';

    public string $statusFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public string $officerFilter = '';

    public string $packageFilter = '';

    public string $categoryFilter = '';

    public function mount(): void
    {
        Gate::authorize('reports.view');
    }

    public function updatingReportType(): void
    {
        $this->resetPage();
        $this->reset(['search', 'statusFilter', 'dateFrom', 'dateTo', 'officerFilter', 'packageFilter', 'categoryFilter']);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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

    public function updatingOfficerFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPackageFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Every report type's query, shared between the on-screen table
     * (paginated) and the CSV export (unpaginated) so the export always
     * reflects exactly what's currently filtered on screen.
     */
    protected function baseQuery()
    {
        return match ($this->reportType) {
            'customers' => Customer::query()
                ->with('user')
                ->when($this->search, fn ($q) => $q->whereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$this->search}%")->orWhere('phone', 'like', "%{$this->search}%")))
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
                ->latest(),

            'referrals' => Referral::query()
                ->with(['officer', 'customer.user'])
                ->when($this->officerFilter, fn ($q) => $q->where('officer_id', $this->officerFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('registered_at', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('registered_at', '<=', $this->dateTo))
                ->latest('registered_at'),

            'applications' => Application::query()
                ->with(['customer.user', 'package'])
                ->when($this->packageFilter, fn ($q) => $q->where('package_id', $this->packageFilter))
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('application_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('application_date', '<=', $this->dateTo))
                ->latest('application_date'),

            'salary' => PayrollItem::query()
                ->with(['officer', 'payroll'])
                ->when($this->officerFilter, fn ($q) => $q->where('officer_id', $this->officerFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereHas('payroll', fn ($pq) => $pq->whereDate('month', '>=', $this->dateFrom)))
                ->when($this->dateTo, fn ($q) => $q->whereHas('payroll', fn ($pq) => $pq->whereDate('month', '<=', $this->dateTo)))
                ->latest('id'),

            'expenses' => Expense::query()
                ->with('category')
                ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
                ->when($this->dateFrom, fn ($q) => $q->whereDate('expense_date', '>=', $this->dateFrom))
                ->when($this->dateTo, fn ($q) => $q->whereDate('expense_date', '<=', $this->dateTo))
                ->latest('expense_date'),

            default => Customer::query(),
        };
    }

    public function export(): StreamedResponse
    {
        Gate::authorize('reports.export');

        [$headers, $rows] = match ($this->reportType) {
            'customers' => [
                ['Name', 'Phone', 'Status', 'Registered'],
                $this->baseQuery()->get()->map(fn (Customer $c) => [$c->user?->name ?? 'Unknown', $c->user?->phone, $c->status->label(), $c->created_at->format('Y-m-d')]),
            ],
            'referrals' => [
                ['Officer', 'Customer', 'Referral Code', 'Registered'],
                $this->baseQuery()->get()->map(fn (Referral $r) => [$r->officer?->name ?? 'Unknown', $r->customer?->user?->name ?? 'Unknown', $r->referral_code, $r->registered_at->format('Y-m-d')]),
            ],
            'applications' => [
                ['Application #', 'Customer', 'Package', 'Price', 'Status', 'Applied'],
                $this->baseQuery()->get()->map(fn (Application $a) => [$a->application_number, $a->customer?->user?->name ?? 'Unknown', $a->package?->name ?? 'Unknown', $a->package_price, $a->status->label(), $a->application_date->format('Y-m-d')]),
            ],
            'salary' => [
                ['Officer', 'Month', 'Basic', 'Allowance', 'Deduction', 'Net Salary'],
                $this->baseQuery()->get()->map(fn (PayrollItem $i) => [$i->officer?->name ?? 'Unknown', $i->payroll->month->format('Y-m'), $i->basic_salary, $i->total_allowance, $i->total_deduction, $i->net_salary]),
            ],
            'expenses' => [
                ['Date', 'Category', 'Amount', 'Paid To', 'Reference'],
                $this->baseQuery()->get()->map(fn (Expense $e) => [$e->expense_date->format('Y-m-d'), $e->category->name, $e->amount, $e->paid_by, $e->reference]),
            ],
            default => [[], collect()],
        };

        $filename = "{$this->reportType}-report-".now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        $rows = $this->reportType === 'financial' ? null : $this->baseQuery()->paginate(15);

        return view('livewire.admin.reports.index', [
            'rows' => $rows,
            'officers' => Officer::with('user')->get(),
            'packages' => Package::orderBy('name')->get(),
            'categories' => ExpenseCategory::orderBy('name')->get(),
            'topOfficers' => $this->reportType === 'referrals' ? $this->topPerformingOfficers() : null,
            'financialTotals' => $this->reportType === 'financial' ? $this->financialTotals() : null,
        ]);
    }

    protected function topPerformingOfficers()
    {
        return Officer::query()
            ->with('user')
            ->get()
            ->map(function (Officer $officer) {
                $referralCount = Referral::where('officer_id', $officer->user_id)->count();
                $activeCount = Referral::where('officer_id', $officer->user_id)
                    ->whereHas('customer', fn ($q) => $q->where('status', 'active'))
                    ->count();

                return ['officer' => $officer, 'referrals' => $referralCount, 'active' => $activeCount];
            })
            ->sortByDesc('referrals')
            ->values();
    }

    protected function financialTotals(): array
    {
        $totalPackageValue = Application::where('status', ApplicationStatus::Approved)
            ->when($this->dateFrom, fn ($q) => $q->whereDate('application_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('application_date', '<=', $this->dateTo))
            ->sum('package_price');

        $totalSalaryPaid = \App\Models\SalaryPayment::query()
            ->when($this->dateFrom, fn ($q) => $q->whereDate('payment_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('payment_date', '<=', $this->dateTo))
            ->sum('amount');

        $totalExpense = Expense::query()
            ->when($this->dateFrom, fn ($q) => $q->whereDate('expense_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('expense_date', '<=', $this->dateTo))
            ->sum('amount');

        $totalExpenseCombined = bcadd((string) $totalSalaryPaid, (string) $totalExpense, 2);

        return [
            'totalPackageValue' => $totalPackageValue,
            'totalSalaryPaid' => $totalSalaryPaid,
            'totalExpense' => $totalExpenseCombined,
            'netBalance' => bcsub((string) $totalPackageValue, $totalExpenseCombined, 2),
        ];
    }
}
