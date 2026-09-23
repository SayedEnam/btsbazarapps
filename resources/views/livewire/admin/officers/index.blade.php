<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Officers</h1>
        @can('officers.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Officer
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name, email, employee id...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-3">
                    <select wire:model.live="departmentFilter" class="form-select">
                        <option value="">All Departments</option>
                        @foreach ($departments as $department)
                            @if ($department->children->isNotEmpty())
                                <optgroup label="{{ $department->name }}">
                                    <option value="{{ $department->id }}">{{ $department->name }} (incl. sub-departments)</option>
                                    @foreach ($department->children as $child)
                                        <option value="{{ $child->id }}">{{ $child->name }}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Officer</th>
                            <th>Employee ID</th>
                            <th>Referral Code</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Referrals</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($officers as $officer)
                            <tr wire:key="officer-{{ $officer->id }}">
                                <td>
                                    <div>{{ $officer->user?->name ?? 'Unknown user' }}</div>
                                    <div class="text-muted small">{{ $officer->user?->email }} @if ($officer->user?->username) &middot; {{ '@'.$officer->user->username }} @endif</div>
                                </td>
                                <td>{{ $officer->employee_id }}</td>
                                <td>
                                    @if ($officer->user?->referral_code)
                                        <div class="d-flex align-items-center gap-1">
                                            <code>{{ $officer->user->referral_code }}</code>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link text-muted p-0 copy-referral-code-btn"
                                                data-code="{{ $officer->user->referral_code }}"
                                                title="Copy referral code"
                                            >
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $officer->department?->fullName() ?: '-' }}</td>
                                <td>{{ $officer->designation?->name ?: '-' }}</td>
                                <td>{{ $officer->referrals_count }}</td>
                                <td><span class="badge {{ $officer->status->badgeClass() }}">{{ $officer->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('officers.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $officer->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('officers.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $officer->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No officers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $officers->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Officer' : 'Add Officer' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Account</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.phone" class="form-control @error('form.phone') is-invalid @enderror">
                                    @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" wire:model.blur="form.email" class="form-control @error('form.email') is-invalid @enderror">
                                    @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.username" class="form-control @error('form.username') is-invalid @enderror" autocomplete="username">
                                    @error('form.username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Password
                                        @unless ($editingId) <span class="text-danger">*</span> @endunless
                                        @if ($editingId) <span class="text-muted small">(leave blank to keep current)</span> @endif
                                    </label>
                                    <input type="password" wire:model.blur="form.password" class="form-control @error('form.password') is-invalid @enderror" autocomplete="new-password">
                                    @error('form.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Officer Profile</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.employee_id" class="form-control @error('form.employee_id') is-invalid @enderror" placeholder="EMP-0001">
                                    @error('form.employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @unless ($editingId)
                                        <div class="form-text">Auto-generated — you can change it if needed.</div>
                                    @endunless
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Department</label>
                                    <select wire:model.blur="form.department_id" class="form-select @error('form.department_id') is-invalid @enderror">
                                        <option value="">-- None --</option>
                                        @foreach ($departments as $department)
                                            @if ($department->children->isNotEmpty())
                                                <optgroup label="{{ $department->name }}">
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                    @foreach ($department->children as $child)
                                                        <option value="{{ $child->id }}">{{ $child->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('form.department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Designation</label>
                                    <select wire:model.blur="form.designation_id" class="form-select @error('form.designation_id') is-invalid @enderror">
                                        <option value="">-- None --</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('form.designation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Joining Date</label>
                                    <input type="date" wire:model.blur="form.joining_date" class="form-control @error('form.joining_date') is-invalid @enderror">
                                    @error('form.joining_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Basic Salary</label>
                                    <input type="number" step="0.01" wire:model.blur="form.basic_salary" class="form-control @error('form.basic_salary') is-invalid @enderror">
                                    @error('form.basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea wire:model.blur="form.address" rows="2" class="form-control @error('form.address') is-invalid @enderror"></textarea>
                                    @error('form.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Profile Picture</label>
                                    <input type="file" wire:model="form.profile_picture" class="form-control @error('form.profile_picture') is-invalid @enderror" accept="image/*">
                                    @error('form.profile_picture') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5 class="modal-title">Remove Officer</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        This removes the officer profile only — the user account, login, and referral history are kept.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Remove</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @once
        <script>
            document.addEventListener('click', function (event) {
                const btn = event.target.closest('.copy-referral-code-btn');
                if (!btn) return;

                navigator.clipboard.writeText(btn.dataset.code).then(() => {
                    const icon = btn.querySelector('i');
                    icon.classList.remove('bi-clipboard');
                    icon.classList.add('bi-check-lg', 'text-success');
                    setTimeout(() => {
                        icon.classList.remove('bi-check-lg', 'text-success');
                        icon.classList.add('bi-clipboard');
                    }, 1200);
                });
            });
        </script>
    @endonce
</div>
