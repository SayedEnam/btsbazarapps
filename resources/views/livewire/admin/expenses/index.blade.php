<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Expenses</h1>
        @can('expenses.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Expense
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search description, payee, reference...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-md-2" style="max-width: 150px;">
                    <input type="date" wire:model.live="dateFrom" class="form-control" title="From date">
                </div>
                <div class="col-3 col-md-2" style="max-width: 150px;">
                    <input type="date" wire:model.live="dateTo" class="form-control" title="To date">
                </div>
            </div>

            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">Total for current filters</span>
                <span class="fw-bold">৳{{ number_format((float) $totalFiltered, 2) }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Paid To</th>
                            <th>Reference</th>
                            <th>Attachment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $expense)
                            <tr wire:key="expense-{{ $expense->id }}">
                                <td class="text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td>{{ $expense->category->name }}</td>
                                <td class="fw-semibold">৳{{ number_format((float) $expense->amount, 2) }}</td>
                                <td>{{ $expense->paid_by ?: '-' }}</td>
                                <td>{{ $expense->reference ?: '-' }}</td>
                                <td>
                                    @if ($expense->attachmentUrl())
                                        <a href="{{ $expense->attachmentUrl() }}" target="_blank" rel="noopener"><i class="bi bi-paperclip"></i> View</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    @can('expenses.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $expense->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('expenses.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $expense->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No expenses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $expenses->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Expense' : 'Add Expense' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.category_id" class="form-select @error('form.category_id') is-invalid @enderror">
                                        <option value="">-- Select --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.amount" class="form-control @error('form.amount') is-invalid @enderror">
                                    @error('form.amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                                    <input type="date" wire:model.blur="form.expense_date" class="form-control @error('form.expense_date') is-invalid @enderror">
                                    @error('form.expense_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Paid To <span class="text-muted small">(optional)</span></label>
                                    <input type="text" wire:model.blur="form.paid_by" class="form-control @error('form.paid_by') is-invalid @enderror">
                                    @error('form.paid_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Reference <span class="text-muted small">(optional)</span></label>
                                    <input type="text" wire:model.blur="form.reference" class="form-control @error('form.reference') is-invalid @enderror">
                                    @error('form.reference') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Attachment <span class="text-muted small">(receipt/invoice, optional)</span></label>
                                    <input type="file" wire:model="form.attachment" class="form-control @error('form.attachment') is-invalid @enderror" accept="image/*,.pdf">
                                    @error('form.attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description <span class="text-muted small">(optional)</span></label>
                                    <textarea wire:model.blur="form.description" rows="2" class="form-control @error('form.description') is-invalid @enderror"></textarea>
                                    @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

    @if ($deletingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Expense</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this expense record?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
