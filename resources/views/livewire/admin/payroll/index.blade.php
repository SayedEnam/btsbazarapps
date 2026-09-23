<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Payroll</h1>
        @can('payroll.create')
            <button type="button" class="btn btn-brand" wire:click="openGenerate">
                <i class="bi bi-plus-lg me-1"></i> Generate Payroll
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Officers</th>
                            <th>Total Net Salary</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr wire:key="payroll-{{ $payroll->id }}">
                                <td class="fw-semibold">{{ $payroll->month->format('F Y') }}</td>
                                <td>{{ $payroll->items_count }}</td>
                                <td>৳{{ number_format((float) $payroll->totalNetSalary(), 2) }}</td>
                                <td><span class="badge {{ $payroll->status->badgeClass() }}">{{ $payroll->status->label() }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="view({{ $payroll->id }})" title="View">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @can('payroll.approve')
                                            @if ($payroll->status === \App\Enums\PayrollStatus::Draft)
                                                <button type="button" class="btn btn-sm btn-outline-success" wire:click="approve({{ $payroll->id }})" title="Approve">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            @endif
                                        @endcan
                                        @can('payroll.pay')
                                            @if ($payroll->status === \App\Enums\PayrollStatus::Approved)
                                                <button type="button" class="btn btn-sm btn-brand" wire:click="markPaid({{ $payroll->id }})" title="Mark Paid">
                                                    <i class="bi bi-cash-coin"></i> Mark Paid
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No payroll generated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $payrolls->links() }}</div>
        </div>
    </div>

    {{-- Generate modal --}}
    @if ($showGenerateModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="generate">
                        <div class="modal-header">
                            <h5 class="modal-title">Generate Payroll</h5>
                            <button type="button" class="btn-close" wire:click="$set('showGenerateModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Month <span class="text-danger">*</span></label>
                            <input type="month" wire:model.blur="generateMonth" class="form-control @error('generateMonth') is-invalid @enderror">
                            @error('generateMonth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Creates one payroll item per officer with an active salary profile. Running this again for the same month only adds officers who don't already have an item.</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('showGenerateModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-brand">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- View modal --}}
    @if ($viewing)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payroll — {{ $viewing->month->format('F Y') }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('viewingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Officer</th>
                                        <th>Basic</th>
                                        <th>Allowance</th>
                                        <th>Deduction</th>
                                        <th>Net Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($viewing->items as $item)
                                        <tr>
                                            <td>{{ $item->officer?->name ?? 'Unknown officer' }}</td>
                                            <td>৳{{ number_format((float) $item->basic_salary, 2) }}</td>
                                            <td>৳{{ number_format((float) $item->total_allowance, 2) }}</td>
                                            <td>৳{{ number_format((float) $item->total_deduction, 2) }}</td>
                                            <td class="fw-semibold">৳{{ number_format((float) $item->net_salary, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('viewingId', null)">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
