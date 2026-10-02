<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-0">All Referrals</h1>
            <p class="text-muted mb-0 small">Total Registered: <strong>{{ $totalReferrals }}</strong></p>
        </div>
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
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Mobile</th>
                            <th>Referral Code</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Reg. Date</th>
                            <th>Reg. Member</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr wire:key="referral-{{ $referral->id }}">
                                <td>
                                    @if ($referral->customer?->user)
                                        <a href="{{ route('officer.referrals.customer.list', $referral->customer->id) }}" wire:navigate class="text-decoration-none">{{ $referral->customer->user->name }}</a>
                                    @else
                                        Unknown customer
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $application = $referral->customer?->applications->sortByDesc('application_date')->first();
                                    @endphp
                                    {{ $application?->designation?->name ?? '-' }}
                                </td>
                                <td>{{ $referral->customer?->user?->phone ?: '-' }}</td>
                                <td><code>{{ $referral->customer?->user?->referral_code ?? '-' }}</code></td>
                                <td>{{ $referral->customer?->applications->sortByDesc('application_date')->first()?->package?->name ?? '-' }}</td>
                                <td>৳{{ number_format((float) $referral->customer?->applications->sortByDesc('application_date')->first()?->package_price ?? 0, 2) }}</td>
                                <td class="text-muted">{{ $referral->registered_at->format('d M Y') }}</td>
                                <td>{{ $referralCounts[$referral->customer?->user?->referral_code] ?? 0 }}</td>
                                <td>
                                    @if ($referral->customer)
                                        <span class="badge {{ $referral->customer->status->badgeClass() }}">{{ $referral->customer->status->label() }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No referrals found.
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
