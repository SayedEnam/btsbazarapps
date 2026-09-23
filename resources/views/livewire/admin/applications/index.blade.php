<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Applications</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="App #, name or mobile...">
                </div>
                <div class="col-6 col-md-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach (\App\Enums\ApplicationStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select wire:model.live="packageFilter" class="form-select">
                        <option value="">All Packages</option>
                        @foreach ($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select wire:model.live="officerFilter" class="form-select">
                        <option value="">All Officers</option>
                        @foreach ($officers as $officer)
                            <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-md-1_5" style="max-width: 140px;">
                    <input type="date" wire:model.live="dateFrom" class="form-control" title="From date">
                </div>
                <div class="col-3 col-md-1_5" style="max-width: 140px;">
                    <input type="date" wire:model.live="dateTo" class="form-control" title="To date">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>App #</th>
                            <th>Customer</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Officer</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr wire:key="application-{{ $application->id }}">
                                <td><code>{{ $application->application_number }}</code></td>
                                <td>
                                    <div>{{ $application->customer?->user?->name ?? 'Unknown customer' }}</div>
                                    <div class="text-muted small">{{ $application->customer?->user?->phone ?: '-' }}</div>
                                </td>
                                <td>{{ $application->package?->name ?? 'Unknown package' }}</td>
                                <td>৳{{ number_format((float) $application->package_price, 2) }}</td>
                                <td>{{ $application->officer?->name ?: '-' }}</td>
                                <td class="text-muted">{{ $application->application_date->format('d M Y') }}</td>
                                <td><span class="badge {{ $application->status->badgeClass() }}">{{ $application->status->label() }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="view({{ $application->id }})" title="View">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @can('applications.approve')
                                            @if ($application->status === \App\Enums\ApplicationStatus::Pending)
                                                <button type="button" class="btn btn-sm btn-outline-info" wire:click="markUnderReview({{ $application->id }})" title="Mark Under Review">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            @endif
                                            @if (in_array($application->status, [\App\Enums\ApplicationStatus::Pending, \App\Enums\ApplicationStatus::UnderReview]))
                                                <button type="button" class="btn btn-sm btn-outline-success" wire:click="approve({{ $application->id }})" title="Approve">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        @can('applications.reject')
                                            @if (in_array($application->status, [\App\Enums\ApplicationStatus::Pending, \App\Enums\ApplicationStatus::UnderReview]))
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmReject({{ $application->id }})" title="Reject">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            @endif
                                        @endcan
                                        @can('applications.reassign')
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="confirmReassign({{ $application->id }})" title="Reassign Officer">
                                                <i class="bi bi-person-gear"></i>
                                            </button>
                                        @endcan
                                        @can('applications.cancel')
                                            @if (! in_array($application->status, [\App\Enums\ApplicationStatus::Cancelled, \App\Enums\ApplicationStatus::Rejected]))
                                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="confirmCancel({{ $application->id }})" title="Cancel">
                                                    <i class="bi bi-slash-circle"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $applications->links() }}</div>
        </div>
    </div>

    {{-- View details modal --}}
    @if ($viewing)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Application {{ $viewing->application_number }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('viewingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <dl class="row mb-0 small">
                                    <dt class="col-5">Customer</dt>
                                    <dd class="col-7">{{ $viewing->customer?->user?->name ?? 'Unknown customer' }}</dd>
                                    <dt class="col-5">Mobile</dt>
                                    <dd class="col-7">{{ $viewing->customer?->user?->phone ?: '-' }}</dd>
                                    <dt class="col-5">Package</dt>
                                    <dd class="col-7">{{ $viewing->package?->name ?? 'Unknown package' }}</dd>
                                    <dt class="col-5">Price</dt>
                                    <dd class="col-7">৳{{ number_format((float) $viewing->package_price, 2) }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row mb-0 small">
                                    <dt class="col-5">Officer</dt>
                                    <dd class="col-7">{{ $viewing->officer?->name ?: '-' }}</dd>
                                    <dt class="col-5">Applied</dt>
                                    <dd class="col-7">{{ $viewing->application_date->format('d M Y') }}</dd>
                                    <dt class="col-5">Status</dt>
                                    <dd class="col-7"><span class="badge {{ $viewing->status->badgeClass() }}">{{ $viewing->status->label() }}</span></dd>
                                    <dt class="col-5">Reviewed By</dt>
                                    <dd class="col-7">{{ $viewing->reviewedBy?->name ?: '-' }}</dd>
                                </dl>
                            </div>
                            @if ($viewing->rejection_reason)
                                <div class="col-12">
                                    <div class="alert alert-danger small mb-0">
                                        <strong>Rejection reason:</strong> {{ $viewing->rejection_reason }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <h6 class="small text-uppercase text-muted fw-bold">Status History</h6>
                        <ul class="list-group list-group-flush">
                            @foreach ($viewing->statusHistories as $history)
                                <li class="list-group-item px-0 small">
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            @if ($history->old_status)
                                                {{ $history->old_status->label() }} &rarr; <strong>{{ $history->new_status->label() }}</strong>
                                            @else
                                                Submitted as <strong>{{ $history->new_status->label() }}</strong>
                                            @endif
                                        </span>
                                        <span class="text-muted">{{ $history->changed_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                    <div class="text-muted">
                                        by {{ $history->changedBy?->name ?: 'System' }}
                                        @if ($history->reason) — {{ $history->reason }} @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('viewingId', null)">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject modal --}}
    @if ($rejectingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="reject">
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Application</h5>
                            <button type="button" class="btn-close" wire:click="$set('rejectingId', null)"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea wire:model.blur="rejectionReason" rows="3" class="form-control @error('rejectionReason') is-invalid @enderror"></textarea>
                            @error('rejectionReason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('rejectingId', null)">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Cancel modal --}}
    @if ($cancellingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="cancel">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancel Application</h5>
                            <button type="button" class="btn-close" wire:click="$set('cancellingId', null)"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Reason <span class="text-muted small">(optional)</span></label>
                            <textarea wire:model="cancellationReason" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('cancellingId', null)">Back</button>
                            <button type="submit" class="btn btn-danger">Cancel Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Reassign officer modal --}}
    @if ($reassigningId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="reassign">
                        <div class="modal-header">
                            <h5 class="modal-title">Reassign Officer</h5>
                            <button type="button" class="btn-close" wire:click="$set('reassigningId', null)"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Officer</label>
                            <select wire:model.blur="newOfficerId" class="form-select @error('newOfficerId') is-invalid @enderror">
                                <option value="">-- Unassigned --</option>
                                @foreach ($officers as $officer)
                                    <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                                @endforeach
                            </select>
                            @error('newOfficerId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('reassigningId', null)">Cancel</button>
                            <button type="submit" class="btn btn-brand">Reassign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
