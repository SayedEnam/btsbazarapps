<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-0">{{ $customer->user?->name }}'s Referrals</h1>
            <p class="text-muted mb-0">Users registered with referral code: <code>{{ $customer->user?->referral_code }}</code></p>
            <p class="text-muted mb-0 small">Total Registered: <strong>{{ $totalReferrals }}</strong></p>
        </div>
        <a href="{{ route('officer.referrals.all') }}" class="btn btn-outline-secondary btn-sm" wire:navigate>Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name or mobile...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Mobile</th>
                            <th>Package</th>
                            <th>Price</th>
                            <th>Reg. Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr wire:key="referral-{{ $referral->id }}">
                                <td>{{ $referral->customer?->user?->name ?? 'Unknown customer' }}</td>
                                <td>
                                    @php
                                        $application = $referral->customer?->applications->sortByDesc('application_date')->first();
                                    @endphp
                                    {{ $application?->designation?->name ?? '-' }}
                                </td>
                                <td>{{ $referral->customer?->user?->phone ?: '-' }}</td>
                                <td>{{ $referral->customer?->applications->sortByDesc('application_date')->first()?->package?->name ?? '-' }}</td>
                                <td>৳{{ number_format((float) $referral->customer?->applications->sortByDesc('application_date')->first()?->package_price ?? 0, 2) }}</td>
                                <td class="text-muted">{{ $referral->registered_at->format('d M Y') }}</td>
                                <td>
                                    @if ($referral->customer?->user)
                                        <i class="bi bi-person me-1 text-muted"></i>
                                        <a href="{{ route('officer.referrals.customer.list', $referral->customer->id) }}" wire:navigate class="text-decoration-none">{{ $referral->customer->user->name }}</a>
                                    @else
                                        Unknown customer
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No referrals found for this user.
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
