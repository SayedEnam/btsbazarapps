<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Financial Transactions</h1>
        @can('financial-transactions.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Record Transaction
            </button>
        @endcan
    </div>

    <div class="alert alert-light border small mb-3">
        <i class="bi bi-info-circle text-brand me-1"></i>
        A manual bookkeeping ledger for ad-hoc entries only — package value, salary payments, and expenses are
        never auto-recorded here to avoid double-counting. See the Accounts dashboard for those totals.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-6 col-md-3">
                    <select wire:model.live="typeFilter" class="form-select">
                        <option value="">All Types</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Recorded By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr wire:key="transaction-{{ $transaction->id }}">
                                <td class="text-muted">{{ $transaction->transaction_date->format('d M Y') }}</td>
                                <td><span class="badge {{ $transaction->transaction_type->badgeClass() }}">{{ $transaction->transaction_type->label() }}</span></td>
                                <td class="fw-semibold">৳{{ number_format((float) $transaction->amount, 2) }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->createdBy->name }}</td>
                                <td class="text-end">
                                    @can('financial-transactions.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $transaction->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No transactions recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $transactions->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">Record Transaction</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select wire:model.blur="form.transaction_type" class="form-select @error('form.transaction_type') is-invalid @enderror">
                                    <option value="income">Income</option>
                                    <option value="expense">Expense</option>
                                </select>
                                @error('form.transaction_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model.blur="form.amount" class="form-control @error('form.amount') is-invalid @enderror">
                                @error('form.amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" wire:model.blur="form.transaction_date" class="form-control @error('form.transaction_date') is-invalid @enderror">
                                @error('form.transaction_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea wire:model.blur="form.description" rows="3" class="form-control @error('form.description') is-invalid @enderror"></textarea>
                                @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    @if ($deletingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Transaction</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this transaction?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
