<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Accounts</h1>
    </div>

    <div class="alert alert-warning small mb-4">
        <i class="bi bi-exclamation-triangle me-1"></i>
        <strong>This is a system accounting calculation, not a real payment gateway balance.</strong>
        Monthly Bazar has no online payment processor — these figures are computed from records entered
        into the system (approved applications, recorded salary payments, and recorded expenses), not from
        an actual bank or wallet balance.
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Package Value</div>
                    <div class="stat-value">৳{{ number_format((float) $totalPackageValue, 2) }}</div>
                    <div class="text-muted small">from approved applications</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Salary Paid</div>
                    <div class="stat-value">৳{{ number_format((float) $totalSalaryPaid, 2) }}</div>
                    <div class="text-muted small">from salary payments</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Other Expense</div>
                    <div class="stat-value">৳{{ number_format((float) $totalOtherExpense, 2) }}</div>
                    <div class="text-muted small">from recorded expenses</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Total Expense</div>
                    <div class="stat-value">৳{{ number_format((float) $totalExpense, 2) }}</div>
                    <div class="text-muted small">salary + other expense</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted small mb-1">Net Balance</div>
                <div class="h3 mb-0 {{ (float) $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                    ৳{{ number_format((float) $netBalance, 2) }}
                </div>
            </div>
            <div class="text-muted small text-end" style="max-width: 320px;">
                Total Package Value − Total Expense. A system-computed figure for internal tracking only.
            </div>
        </div>
    </div>
</div>
