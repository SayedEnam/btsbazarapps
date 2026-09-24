<div>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-muted mb-0">Your referral code: <code>{{ auth()->user()->referral_code }}</code></p>
            @php $referralUrl = rtrim(config('app.url'), '/') . '/r/' . auth()->user()->referral_code; @endphp
            <p class="text-muted mb-0 small">Your referral link: <span id="referralUrlText">{{ $referralUrl }}</span> <button type="button" class="btn btn-sm btn-link p-0 ms-1" onclick="navigator.clipboard.writeText(document.getElementById('referralUrlText').innerText).then(() => alert('Referral link copied!'))" title="Copy referral link"><i class="bi bi-clipboard"></i></button></p>
            <p class="mb-0">
                <a href="{{ route('officer.profile') }}" class="text-decoration-none small" wire:navigate>Get Referral Link</a>
            </p>
        </div>
        <a href="{{ route('officer.profile') }}" class="btn btn-outline-brand" wire:navigate>
            <i class="bi bi-link-45deg me-1"></i> Get Referral Link
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Total Referrals</div>
                        <div class="stat-value">{{ $totalReferrals }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#146356;"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Active Members</div>
                        <div class="stat-value">{{ $activeMembers }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#1b8a6b;"><i class="bi bi-person-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Pending</div>
                        <div class="stat-value">{{ $pendingMembers }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#fd7e14;"><i class="bi bi-hourglass-split"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small">Inactive / Suspended</div>
                        <div class="stat-value">{{ $inactiveOrSuspended }}</div>
                    </div>
                    <div class="stat-icon" style="background-color:#6c757d;"><i class="bi bi-person-dash"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="h6 mb-3">Referral Status Breakdown</h2>
                    @if ($totalReferrals > 0)
                        <canvas id="referralStatusChart" height="220"></canvas>
                    @else
                        <p class="text-muted small mb-0">No referrals yet — share your referral link to get started.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card mb-3">
                <div class="card-body">
                    <h2 class="h6 mb-3"><i class="bi bi-file-earmark-text text-brand me-1"></i> Application Stats</h2>
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="stat-value">{{ $pendingApplications }}</div>
                            <div class="text-muted small">Pending / Review</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-value">{{ $approvedApplications }}</div>
                            <div class="text-muted small">Approved</div>
                        </div>
                        <div class="col-4">
                            <div class="stat-value">{{ $rejectedApplications }}</div>
                            <div class="text-muted small">Rejected</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total Approved Package Value</span>
                        <span class="fw-bold text-brand">৳{{ number_format((float) $totalPackageValue, 2) }}</span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('officer.applications.index') }}" class="btn btn-outline-brand btn-sm" wire:navigate>
                            <i class="bi bi-file-earmark-text me-1"></i> View Applications
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
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

    @if ($totalReferrals > 0)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new Chart(document.getElementById('referralStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            data: @json($chartData),
                            backgroundColor: ['#1b8a6b', '#fd7e14', '#6c757d', '#dc3545'],
                        }],
                    },
                    options: { plugins: { legend: { position: 'bottom' } } },
                });
            });
        </script>
    @endif
</div>
