<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Referral Management</h1>
    </div>

    <div class="alert alert-light border small mb-3">
        <i class="bi bi-info-circle me-1"></i>
        A referral relationship is permanent once a customer registers through an officer's link — this is a
        read-only audit view. Reassigning who currently handles a specific application is done from the
        <a href="{{ route('admin.applications.index') }}" wire:navigate>Applications</a> screen instead.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search officer, customer, or referral code...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="officerFilter" class="form-select">
                        <option value="">All Officers</option>
                        @foreach ($officers as $officer)
                            <option value="{{ $officer->user_id }}">{{ $officer->user?->name ?? 'Unknown' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Officer</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Referral Code</th>
                            <th>Membership Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr wire:key="referral-{{ $referral->id }}">
                                <td>{{ $referral->officer?->name ?? 'Unknown officer' }}</td>
                                <td>{{ $referral->customer?->user?->name ?? 'Unknown customer' }}</td>
                                <td>{{ $referral->customer?->user?->phone ?: '-' }}</td>
                                <td><code>{{ $referral->referral_code }}</code></td>
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
                                <td colspan="6" class="text-center text-muted py-4">No referrals found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $referrals->links() }}</div>
        </div>
    </div>
</div>
