<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0">Here is a snapshot of your Monthly Bazar system.</p>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Active Customers</div>
                        <div class="stat-value">{{ $activeCustomerCount }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#146356;">
                        <i class="bi bi-person-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <a href="{{ route('admin.applications.index') }}" class="text-decoration-none" wire:navigate>
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">Pending Applications</div>
                            <div class="stat-value">{{ $pendingApplicationCount }}</div>
                        </div>
                        <div class="stat-icon" style="background-color:#fd7e14;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Active Officers</div>
                        <div class="stat-value">{{ $activeOfficerCount }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#0d6efd;">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Revenue</div>
                    <div class="stat-value">৳{{ number_format((float) $totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Users</div>
                    <div class="stat-value">{{ $userCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Applications This Month</div>
                    <div class="stat-value">{{ $applicationsThisMonth }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Expense</div>
                    <div class="stat-value">৳{{ number_format((float) $totalExpense, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Net Balance</div>
                    <div class="stat-value {{ (float) $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                        ৳{{ number_format((float) $netBalance, 2) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h6 mb-0"><i class="bi bi-hourglass-split text-brand me-1"></i> Applications Needing Review</h2>
                        <a href="{{ route('admin.applications.index') }}" class="small" wire:navigate>View all</a>
                    </div>
                    @forelse ($pendingApplications as $application)
                        <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <div class="fw-semibold">{{ $application->customer?->user?->name ?? 'Unknown customer' }}</div>
                                <div class="text-muted small">{{ $application->package?->name ?? 'Unknown package' }} — {{ $application->application_number }}</div>
                            </div>
                            <div class="text-muted small text-end">{{ $application->application_date->format('d M Y') }}</div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No applications are waiting for review.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h6 mb-0"><i class="bi bi-clock-history text-brand me-1"></i> Recent Activity</h2>
                        <a href="{{ route('admin.activity-logs.index') }}" class="small" wire:navigate>View all</a>
                    </div>
                    @forelse ($recentActivity as $log)
                        <div class="d-flex justify-content-between align-items-start py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <div class="small">{{ $log->description }}</div>
                                <div class="text-muted" style="font-size: .75rem;">{{ $log->user?->name ?? 'System' }}</div>
                            </div>
                            <div class="text-muted text-end" style="font-size: .75rem; white-space: nowrap;">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No activity recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
