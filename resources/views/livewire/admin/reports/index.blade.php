<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Reports</h1>
        @can('reports.export')
            @if ($reportType !== 'financial')
                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="export">
                    <i class="bi bi-download me-1"></i> Export CSV
                </button>
            @endif
        @endcan
    </div>

    <ul class="nav nav-pills mb-3 flex-wrap">
        @foreach ([
            'customers' => 'Customers',
            'referrals' => 'Referrals',
            'applications' => 'Packages',
            'salary' => 'Salary',
            'expenses' => 'Expenses',
            'financial' => 'Financial',
        ] as $type => $label)
            <li class="nav-item">
                <button type="button" class="nav-link {{ $reportType === $type ? 'active bg-brand' : '' }}" wire:click="$set('reportType', '{{ $type }}')">
                    {{ $label }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="card">
        <div class="card-body">
            {{-- Filters --}}
            <div class="row g-2 mb-3">
                @if (in_array($reportType, ['customers']))
                    <div class="col-12 col-md-4 col-lg-3">
                        <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name or mobile...">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                @endif

                @if ($reportType === 'referrals')
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="officerFilter" class="form-select">
                            <option value="">All Officers</option>
                            @foreach ($officers as $officer)
                                <option value="{{ $officer->user_id }}">{{ $officer->user?->name ?? 'Unknown' }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($reportType === 'applications')
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="packageFilter" class="form-select">
                            <option value="">All Packages</option>
                            @foreach ($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach (\App\Enums\ApplicationStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($reportType === 'salary')
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="officerFilter" class="form-select">
                            <option value="">All Officers</option>
                            @foreach ($officers as $officer)
                                <option value="{{ $officer->user_id }}">{{ $officer->user?->name ?? 'Unknown' }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($reportType === 'expenses')
                    <div class="col-6 col-md-3 col-lg-2">
                        <select wire:model.live="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if (in_array($reportType, ['customers', 'referrals', 'applications', 'salary', 'expenses', 'financial']))
                    <div class="col-3" style="max-width: 150px;">
                        <input type="date" wire:model.live="dateFrom" class="form-control" title="From date">
                    </div>
                    <div class="col-3" style="max-width: 150px;">
                        <input type="date" wire:model.live="dateTo" class="form-control" title="To date">
                    </div>
                @endif
            </div>

            {{-- Customers --}}
            @if ($reportType === 'customers')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Name</th><th>Phone</th><th>Status</th><th>Registered</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $customer)
                                <tr>
                                    <td>{{ $customer->user?->name ?? 'Unknown customer' }}</td>
                                    <td>{{ $customer->user?->phone ?: '-' }}</td>
                                    <td><span class="badge {{ $customer->status->badgeClass() }}">{{ $customer->status->label() }}</span></td>
                                    <td class="text-muted">{{ $customer->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No customers found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rows->links() }}</div>
            @endif

            {{-- Referrals --}}
            @if ($reportType === 'referrals')
                <h6 class="small text-uppercase text-muted fw-bold mb-2">Top Performing Officers</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm">
                        <thead><tr><th>Officer</th><th>Total Referrals</th><th>Active Members</th></tr></thead>
                        <tbody>
                            @foreach ($topOfficers->take(5) as $row)
                                <tr>
                                    <td>{{ $row['officer']->user?->name ?? 'Unknown officer' }}</td>
                                    <td>{{ $row['referrals'] }}</td>
                                    <td>{{ $row['active'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h6 class="small text-uppercase text-muted fw-bold mb-2">All Referrals</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Officer</th><th>Customer</th><th>Referral Code</th><th>Registered</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $referral)
                                <tr>
                                    <td>{{ $referral->officer?->name ?? 'Unknown officer' }}</td>
                                    <td>{{ $referral->customer?->user?->name ?? 'Unknown customer' }}</td>
                                    <td><code>{{ $referral->referral_code }}</code></td>
                                    <td class="text-muted">{{ $referral->registered_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No referrals found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rows->links() }}</div>
            @endif

            {{-- Applications / Package report --}}
            @if ($reportType === 'applications')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>App #</th><th>Customer</th><th>Package</th><th>Price</th><th>Status</th><th>Applied</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $application)
                                <tr>
                                    <td><code>{{ $application->application_number }}</code></td>
                                    <td>{{ $application->customer?->user?->name ?? 'Unknown customer' }}</td>
                                    <td>{{ $application->package?->name ?? 'Unknown package' }}</td>
                                    <td>৳{{ number_format((float) $application->package_price, 2) }}</td>
                                    <td><span class="badge {{ $application->status->badgeClass() }}">{{ $application->status->label() }}</span></td>
                                    <td class="text-muted">{{ $application->application_date->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No applications found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rows->links() }}</div>
            @endif

            {{-- Salary --}}
            @if ($reportType === 'salary')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Officer</th><th>Month</th><th>Basic</th><th>Allowance</th><th>Deduction</th><th>Net Salary</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $item)
                                <tr>
                                    <td>{{ $item->officer?->name ?? 'Unknown officer' }}</td>
                                    <td>{{ $item->payroll->month->format('F Y') }}</td>
                                    <td>৳{{ number_format((float) $item->basic_salary, 2) }}</td>
                                    <td>৳{{ number_format((float) $item->total_allowance, 2) }}</td>
                                    <td>৳{{ number_format((float) $item->total_deduction, 2) }}</td>
                                    <td class="fw-semibold">৳{{ number_format((float) $item->net_salary, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No salary records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rows->links() }}</div>
            @endif

            {{-- Expenses --}}
            @if ($reportType === 'expenses')
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Date</th><th>Category</th><th>Amount</th><th>Paid To</th><th>Reference</th></tr></thead>
                        <tbody>
                            @forelse ($rows as $expense)
                                <tr>
                                    <td class="text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                                    <td>{{ $expense->category->name }}</td>
                                    <td class="fw-semibold">৳{{ number_format((float) $expense->amount, 2) }}</td>
                                    <td>{{ $expense->paid_by ?: '-' }}</td>
                                    <td>{{ $expense->reference ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No expenses found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $rows->links() }}</div>
            @endif

            {{-- Financial --}}
            @if ($reportType === 'financial')
                <div class="alert alert-warning small">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    A system accounting calculation, not a real payment gateway balance.
                </div>
                <div class="row g-3">
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100"><div class="card-body">
                            <div class="text-muted small mb-2">Total Package Value</div>
                            <div class="stat-value">৳{{ number_format((float) $financialTotals['totalPackageValue'], 2) }}</div>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100"><div class="card-body">
                            <div class="text-muted small mb-2">Total Salary Paid</div>
                            <div class="stat-value">৳{{ number_format((float) $financialTotals['totalSalaryPaid'], 2) }}</div>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100"><div class="card-body">
                            <div class="text-muted small mb-2">Total Expense</div>
                            <div class="stat-value">৳{{ number_format((float) $financialTotals['totalExpense'], 2) }}</div>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card stat-card h-100"><div class="card-body">
                            <div class="text-muted small mb-2">Net Balance</div>
                            <div class="stat-value {{ (float) $financialTotals['netBalance'] >= 0 ? 'text-success' : 'text-danger' }}">
                                ৳{{ number_format((float) $financialTotals['netBalance'], 2) }}
                            </div>
                        </div></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
