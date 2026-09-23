<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">My Profile</h1>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="card mb-3">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Referral Link</h2></div>
                <div class="card-body text-center">
                    <div id="referralQrCode" class="mb-3 d-flex justify-content-center"></div>

                    <div class="input-group mb-2">
                        <input type="text" id="referralLinkInput" class="form-control form-control-sm text-center" value="{{ $referralLink }}" readonly>
                        <button type="button" class="btn btn-sm btn-brand" id="copyReferralLinkBtn">
                            <i class="bi bi-clipboard me-1"></i> Copy
                        </button>
                    </div>
                    <div class="small text-muted">Referral code: <code>{{ auth()->user()->referral_code }}</code></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Officer Details</h2></div>
                <div class="card-body">
                    @if ($officer)
                        <dl class="row mb-0 small">
                            <dt class="col-5">Employee ID</dt>
                            <dd class="col-7">{{ $officer->employee_id }}</dd>
                            <dt class="col-5">Department</dt>
                            <dd class="col-7">{{ $officer->department?->fullName() ?: '-' }}</dd>
                            <dt class="col-5">Designation</dt>
                            <dd class="col-7">{{ $officer->designation?->name ?: '-' }}</dd>
                            <dt class="col-5">Joining Date</dt>
                            <dd class="col-7">{{ $officer->joining_date?->format('d M Y') ?: '-' }}</dd>
                            <dt class="col-5">Status</dt>
                            <dd class="col-7"><span class="badge {{ $officer->status->badgeClass() }}">{{ $officer->status->label() }}</span></dd>
                        </dl>
                    @else
                        <p class="text-muted small mb-0">Your officer profile hasn't been set up by an admin yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card mb-3">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Profile Information</h2></div>
                <div class="card-body">
                    <form wire:submit="updateProfile">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" wire:model="username" class="form-control @error('username') is-invalid @enderror" autocomplete="username">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-brand">Save Changes</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Change Password</h2></div>
                <div class="card-body">
                    <form wire:submit="updatePassword">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" wire:model="passwordForm.current_password" class="form-control @error('passwordForm.current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('passwordForm.current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" wire:model="passwordForm.password" class="form-control @error('passwordForm.password') is-invalid @enderror" autocomplete="new-password">
                            @error('passwordForm.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" wire:model="passwordForm.password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-brand">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const link = @json($referralLink);

            new QRCode(document.getElementById('referralQrCode'), { text: link, width: 180, height: 180 });

            document.getElementById('copyReferralLinkBtn').addEventListener('click', function () {
                navigator.clipboard.writeText(link).then(() => {
                    const btn = this;
                    const original = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Copied!';
                    setTimeout(() => { btn.innerHTML = original; }, 1500);
                });
            });
        });
    </script>
</div>
