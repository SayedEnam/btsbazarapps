<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">My Referrals</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name or mobile...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Membership Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr wire:key="referral-{{ $referral->id }}">
                                <td>{{ $referral->customer?->user?->name ?? 'Unknown customer' }}</td>
                                <td>{{ $referral->customer?->user?->phone ?: '-' }}</td>
                                <td>
                                    @if ($referral->customer)
                                        <span class="badge {{ $referral->customer->status->badgeClass() }}">{{ $referral->customer->status->label() }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $referral->registered_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No referrals yet. Share your referral link to start growing your network.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $referrals->links() }}</div>
        </div>
    </div>
</div>
