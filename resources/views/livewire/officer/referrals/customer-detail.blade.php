<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h4 mb-0">Customer Details</h1>
            <p class="text-muted mb-0">Referred on {{ $referral?->registered_at?->format('d M Y') }}</p>
        </div>
        <a href="{{ route('officer.referrals.all') }}" class="btn btn-outline-secondary btn-sm" wire:navigate>Back</a>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white"><h2 class="h6 mb-0">Customer Information</h2></div>
        <div class="card-body">
            @if ($customer)
                <dl class="row mb-0 small">
                    <dt class="col-4 col-md-3">Name</dt>
                    <dd class="col-8 col-md-9">{{ $customer->user?->name ?? '-' }}</dd>

                    <dt class="col-4 col-md-3">Mobile</dt>
                    <dd class="col-8 col-md-9">{{ $customer->user?->phone ?: '-' }}</dd>

                    <dt class="col-4 col-md-3">Email</dt>
                    <dd class="col-8 col-md-9">{{ $customer->user?->email ?? '-' }}</dd>

                    <dt class="col-4 col-md-3">Address</dt>
                    <dd class="col-8 col-md-9">{{ $customer->address ?: '-' }}</dd>

                    <dt class="col-4 col-md-3">Status</dt>
                    <dd class="col-8 col-md-9">
                        <span class="badge {{ $customer->status->badgeClass() }}">{{ $customer->status->label() }}</span>
                    </dd>
                </dl>
            @else
                <p class="text-muted mb-0">Customer not found.</p>
            @endif
        </div>
    </div>

    @if ($latestApplication)
        <div class="card">
            <div class="card-header bg-white"><h2 class="h6 mb-0">Latest Application</h2></div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-4 col-md-3">Application #</dt>
                    <dd class="col-8 col-md-9"><code>{{ $latestApplication->application_number }}</code></dd>

                    <dt class="col-4 col-md-3">Package</dt>
                    <dd class="col-8 col-md-9">{{ $latestApplication->package?->name ?? 'Unknown package' }}</dd>

                    <dt class="col-4 col-md-3">Price</dt>
                    <dd class="col-8 col-md-9">৳{{ number_format((float) $latestApplication->package_price, 2) }}</dd>

                    <dt class="col-4 col-md-3">Designation</dt>
                    <dd class="col-8 col-md-9">{{ $latestApplication->designation?->name ?? '-' }}</dd>

                    <dt class="col-4 col-md-3">Applied On</dt>
                    <dd class="col-8 col-md-9">{{ $latestApplication->application_date->format('d M Y') }}</dd>

                    <dt class="col-4 col-md-3">Status</dt>
                    <dd class="col-8 col-md-9">
                        <span class="badge {{ $latestApplication->status->badgeClass() }}">{{ $latestApplication->status->label() }}</span>
                    </dd>
                </dl>
            </div>
        </div>
    @endif
</div>
