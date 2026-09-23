<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Salary Payments</h1>
        @can('payroll.pay')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Record Payment
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Officer</th>
                            <th>Month</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Paid By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr wire:key="payment-{{ $payment->id }}">
                                <td>{{ $payment->officer?->name ?? 'Unknown officer' }}</td>
                                <td>{{ $payment->month->format('F Y') }}</td>
                                <td class="fw-semibold">৳{{ number_format((float) $payment->amount, 2) }}</td>
                                <td class="text-muted">{{ $payment->payment_date->format('d M Y') }}</td>
                                <td>{{ $payment->payment_method->label() }}</td>
                                <td>{{ $payment->transaction_reference ?: '-' }}</td>
                                <td>{{ $payment->paidBy->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No salary payments recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $payments->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">Record Salary Payment</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Officer <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.officer_id" class="form-select @error('form.officer_id') is-invalid @enderror">
                                        <option value="">-- Select --</option>
                                        @foreach ($officers as $officer)
                                            <option value="{{ $officer->user_id }}">{{ $officer->user?->name ?? 'Unknown' }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.officer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Related Payroll <span class="text-muted small">(optional)</span></label>
                                    <select wire:model.blur="form.payroll_id" class="form-select @error('form.payroll_id') is-invalid @enderror">
                                        <option value="">-- None --</option>
                                        @foreach ($payrolls as $payroll)
                                            <option value="{{ $payroll->id }}">{{ $payroll->month->format('F Y') }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.payroll_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Month <span class="text-danger">*</span></label>
                                    <input type="month" wire:model.blur="form.month" class="form-control @error('form.month') is-invalid @enderror">
                                    @error('form.month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.amount" class="form-control @error('form.amount') is-invalid @enderror">
                                    @error('form.amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" wire:model.blur="form.payment_date" class="form-control @error('form.payment_date') is-invalid @enderror">
                                    @error('form.payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.payment_method" class="form-select @error('form.payment_method') is-invalid @enderror">
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                        <option value="mobile_banking">Mobile Banking</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('form.payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Transaction Reference <span class="text-muted small">(optional)</span></label>
                                    <input type="text" wire:model.blur="form.transaction_reference" class="form-control @error('form.transaction_reference') is-invalid @enderror">
                                    @error('form.transaction_reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Note <span class="text-muted small">(optional)</span></label>
                                    <textarea wire:model.blur="form.note" rows="2" class="form-control @error('form.note') is-invalid @enderror"></textarea>
                                    @error('form.note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('showModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
