<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Salary Profiles</h1>
        @can('salary-profiles.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Salary Profile
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search officer name...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Officer</th>
                            <th>Basic</th>
                            <th>Allowance</th>
                            <th>Deduction</th>
                            <th>Net Salary</th>
                            <th>Effective From</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($profiles as $profile)
                            <tr wire:key="profile-{{ $profile->id }}">
                                <td>{{ $profile->officer?->name ?? 'Unknown officer' }}</td>
                                <td>৳{{ number_format((float) $profile->basic_salary, 2) }}</td>
                                <td>৳{{ number_format((float) $profile->totalAllowance(), 2) }}</td>
                                <td>৳{{ number_format((float) $profile->deduction, 2) }}</td>
                                <td class="fw-semibold">৳{{ number_format((float) $profile->netSalary(), 2) }}</td>
                                <td class="text-muted">{{ $profile->effective_from->format('d M Y') }}</td>
                                <td><span class="badge {{ $profile->status->badgeClass() }}">{{ $profile->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('salary-profiles.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $profile->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('salary-profiles.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $profile->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No salary profiles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $profiles->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Salary Profile' : 'Add Salary Profile' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Officer <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.officer_id" class="form-select @error('form.officer_id') is-invalid @enderror" {{ $editingId ? 'disabled' : '' }}>
                                        <option value="">-- Select Officer --</option>
                                        @foreach ($officers as $officer)
                                            <option value="{{ $officer->user_id }}">{{ $officer->user?->name ?? 'Unknown' }} ({{ $officer->employee_id }})</option>
                                        @endforeach
                                    </select>
                                    @error('form.officer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Effective From <span class="text-danger">*</span></label>
                                    <input type="date" wire:model.blur="form.effective_from" class="form-control @error('form.effective_from') is-invalid @enderror">
                                    @error('form.effective_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.basic_salary" class="form-control @error('form.basic_salary') is-invalid @enderror">
                                    @error('form.basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">House Allowance <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.house_allowance" class="form-control @error('form.house_allowance') is-invalid @enderror">
                                    @error('form.house_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Transport Allowance <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.transport_allowance" class="form-control @error('form.transport_allowance') is-invalid @enderror">
                                    @error('form.transport_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Mobile Allowance <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.mobile_allowance" class="form-control @error('form.mobile_allowance') is-invalid @enderror">
                                    @error('form.mobile_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Other Allowance <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.other_allowance" class="form-control @error('form.other_allowance') is-invalid @enderror">
                                    @error('form.other_allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Deduction <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.deduction" class="form-control @error('form.deduction') is-invalid @enderror">
                                    @error('form.deduction') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">Setting Active automatically deactivates any other active profile for this officer.</div>
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
                        <h5 class="modal-title">Delete Salary Profile</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure? This does not affect any payroll already generated using this profile.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
