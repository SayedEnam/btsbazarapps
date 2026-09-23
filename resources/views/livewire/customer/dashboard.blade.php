<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0">Here's a snapshot of your Monthly Bazar membership.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Account Status</div>
                    <span class="badge {{ auth()->user()->status->badgeClass() }} fs-6">{{ auth()->user()->status->label() }}</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Membership Status</div>
                    @if ($customer)
                        <span class="badge {{ $customer->status->badgeClass() }} fs-6">{{ $customer->status->label() }}</span>
                    @else
                        <span class="badge bg-secondary fs-6">-</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Package</div>
                    <div class="fw-semibold">{{ $latestApplication ? ($latestApplication->package?->name ?? 'Unknown package') : 'Not applied yet' }}</div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">Referral Officer</div>
                    @if ($customer?->referral?->officer)
                        <div class="fw-semibold">{{ $customer->referral->officer->name }}</div>
                    @else
                        <div class="text-muted">None</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-2"><i class="bi bi-box-seam text-brand me-1"></i> Package &amp; Application</h2>

                    @if ($latestApplication)
                        <dl class="row small mb-3">
                            <dt class="col-4 col-md-3">Application #</dt>
                            <dd class="col-8 col-md-9"><code>{{ $latestApplication->application_number }}</code></dd>
                            <dt class="col-4 col-md-3">Package Value</dt>
                            <dd class="col-8 col-md-9">৳{{ number_format((float) $latestApplication->package_price, 2) }}</dd>
                            <dt class="col-4 col-md-3">Applied On</dt>
                            <dd class="col-8 col-md-9">{{ $latestApplication->application_date->format('d M Y') }}</dd>
                            <dt class="col-4 col-md-3">Status</dt>
                            <dd class="col-8 col-md-9"><span class="badge {{ $latestApplication->status->badgeClass() }}">{{ $latestApplication->status->label() }}</span></dd>
                            @if ($latestApplication->status === \App\Enums\ApplicationStatus::Approved)
                                <dt class="col-4 col-md-3">Approved On</dt>
                                <dd class="col-8 col-md-9">{{ $latestApplication->reviewed_at?->format('d M Y') }}</dd>
                            @endif
                            @if ($latestApplication->status === \App\Enums\ApplicationStatus::Rejected && $latestApplication->rejection_reason)
                                <dt class="col-4 col-md-3">Reason</dt>
                                <dd class="col-8 col-md-9 text-danger">{{ $latestApplication->rejection_reason }}</dd>
                            @endif
                        </dl>

                        @if (in_array($latestApplication->status, [\App\Enums\ApplicationStatus::Rejected, \App\Enums\ApplicationStatus::Cancelled]))
                            <a href="{{ route('customer.apply') }}" class="btn btn-brand btn-sm" wire:navigate>
                                <i class="bi bi-arrow-repeat me-1"></i> Apply Again
                            </a>
                        @endif
                    @else
                        <p class="text-muted mb-3">
                            You haven't applied for a membership package yet. Browse our available packages and
                            apply to activate your membership.
                        </p>
                        <a href="{{ route('customer.apply') }}" class="btn btn-brand" wire:navigate>
                            <i class="bi bi-box-seam me-1"></i> Apply for a Package
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-2"><i class="bi bi-bell text-brand me-1"></i> Notifications</h2>
                    @forelse ($notifications as $notification)
                        <div class="border-bottom py-2 small" wire:key="notif-{{ $notification->id }}">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="text-muted">{{ $notification->data['message'] ?? '' }}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-link p-0" wire:click="markNotificationRead('{{ $notification->id }}')" title="Mark as read">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No notifications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
