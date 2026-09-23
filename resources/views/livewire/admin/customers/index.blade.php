<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Customers</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name, mobile, email, or NID...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Referral Officer</th>
                            <th>Applications</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr wire:key="customer-{{ $customer->id }}">
                                <td>{{ $customer->user?->name ?? 'Unknown user' }}</td>
                                <td>{{ $customer->user?->phone ?: '-' }}</td>
                                <td>{{ $customer->referral?->officer?->name ?: '-' }}</td>
                                <td>{{ $customer->applications_count }}</td>
                                <td><span class="badge {{ $customer->status->badgeClass() }}">{{ $customer->status->label() }}</span></td>
                                <td class="text-muted">{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="view({{ $customer->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $customers->links() }}</div>
        </div>
    </div>

    @if ($viewing)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $viewing->user?->name ?? 'Unknown user' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeView"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Mobile</div>
                                <div>{{ $viewing->user?->phone ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Email</div>
                                <div>{{ $viewing->user?->email ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Father's Name</div>
                                <div>{{ $viewing->father_name ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Mother's Name</div>
                                <div>{{ $viewing->mother_name ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">NID Number</div>
                                <div>{{ $viewing->nid_number ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Profession</div>
                                <div>{{ $viewing->profession ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Date of Birth</div>
                                <div>{{ $viewing->date_of_birth?->format('d M Y') ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Gender</div>
                                <div>{{ $viewing->gender ? ucfirst($viewing->gender) : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Address</div>
                                <div>{{ $viewing->address ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Referral Officer</div>
                                <div>{{ $viewing->referral?->officer?->name ?: '-' }}</div>
                            </div>
                        </div>

                        <h6 class="small text-uppercase text-muted fw-bold mb-2">Applications</h6>
                        <div class="table-responsive mb-3">
                            <table class="table table-sm">
                                <thead><tr><th>App #</th><th>Package</th><th>Price</th><th>Status</th><th>Applied</th></tr></thead>
                                <tbody>
                                    @forelse ($viewing->applications as $application)
                                        <tr>
                                            <td><code>{{ $application->application_number }}</code></td>
                                            <td>{{ $application->package?->name ?? 'Unknown package' }}</td>
                                            <td>৳{{ number_format((float) $application->package_price, 2) }}</td>
                                            <td><span class="badge {{ $application->status->badgeClass() }}">{{ $application->status->label() }}</span></td>
                                            <td class="text-muted">{{ $application->application_date->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted py-3">No applications yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @can('customers.edit')
                            <h6 class="small text-uppercase text-muted fw-bold mb-2">Membership Status</h6>
                            <div class="btn-group">
                                @foreach ($statuses as $status)
                                    <button type="button"
                                        class="btn btn-sm {{ $viewing->status === $status ? 'btn-brand' : 'btn-outline-secondary' }}"
                                        wire:click="updateStatus({{ $viewing->id }}, '{{ $status->value }}')">
                                        {{ $status->label() }}
                                    </button>
                                @endforeach
                            </div>
                        @endcan
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeView">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
