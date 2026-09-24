<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Applications</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="App #, name or mobile...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach (\App\Enums\ApplicationStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>App #</th>
                            <th>Name</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Designation</th>
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
                                <td class="text-muted">{{ $application->application_date->format('d M Y') }}</td>
                                <td><span class="badge {{ $application->status->badgeClass() }}">{{ $application->status->label() }}</span></td>
                                <td>
                                    <select class="form-select form-select-sm" wire:change="updateDesignation({{ $application->id }}, $event.target.value)">
                                        <option value="">No Designation</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ ($application->officer->officer->designation_id ?? null) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-end">
                                    @if (in_array($application->status, [\App\Enums\ApplicationStatus::Pending, \App\Enums\ApplicationStatus::UnderReview]))
                                        <div class="btn-group">
                                            @if ($application->status === \App\Enums\ApplicationStatus::Pending)
                                                <button type="button" class="btn btn-sm btn-outline-info" wire:click="markUnderReview({{ $application->id }})" title="Mark Under Review">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-success" wire:click="approve({{ $application->id }})" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmReject({{ $application->id }})" title="Reject">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No applications assigned to you.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $applications->links() }}</div>
        </div>
    </div>

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
                            <label class="form-label">Rejection Reason</label>
                            <textarea wire:model="rejectionReason" rows="3" class="form-control @error('rejectionReason') is-invalid @enderror"></textarea>
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
</div>
