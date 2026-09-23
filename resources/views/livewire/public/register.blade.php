<div>
    <h4 class="fw-bold text-brand mb-1">Create Your Account</h4>
    <p class="text-muted mb-4">Join Monthly Bazar and start your membership journey.</p>

    @if ($referringOfficerName)
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-person-check-fill fs-4"></i>
            <div>You're registering through a referral from <strong>{{ $referringOfficerName }}</strong>.</div>
        </div>
    @endif

    <form wire:submit="register">
        <h6 class="text-uppercase text-muted small fw-bold mb-3">Account Information</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" wire:model="form.name" class="form-control @error('form.name') is-invalid @enderror">
                @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" wire:model="form.phone" class="form-control @error('form.phone') is-invalid @enderror">
                @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" wire:model="form.email" class="form-control @error('form.email') is-invalid @enderror">
                @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" wire:model="form.username" class="form-control @error('form.username') is-invalid @enderror" autocomplete="username">
                @error('form.username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" wire:model="form.password" class="form-control @error('form.password') is-invalid @enderror" autocomplete="new-password">
                @error('form.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Confirm Password</label>
                <input type="password" wire:model="form.password_confirmation" class="form-control" autocomplete="new-password">
            </div>
        </div>

        <h6 class="text-uppercase text-muted small fw-bold mb-3">Referral <span class="text-muted fw-normal">(optional)</span></h6>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Referral Code</label>
                <input
                    type="text"
                    wire:model.blur="form.referral_code"
                    class="form-control @error('form.referral_code') is-invalid @enderror"
                    placeholder="0001"
                    @if ($referralCodeLocked) readonly @endif
                >
                @error('form.referral_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if ($referralCodeLocked)
                    <div class="form-text">Locked in from your referral link.</div>
                @else
                    <div class="form-text">If a Marketing Officer referred you, enter their referral code here.</div>
                @endif
            </div>
        </div>

        <h6 class="text-uppercase text-muted small fw-bold mb-3">Profile Photo <span class="text-muted fw-normal">(optional)</span></h6>
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-auto">
                <div class="rounded-circle border d-flex align-items-center justify-content-center overflow-hidden bg-light flex-shrink-0" style="width: 96px; height: 96px;">
                    @if ($form->profile_photo && $form->profile_photo->isPreviewable())
                        <img src="{{ $form->profile_photo->temporaryUrl() }}" alt="Profile photo preview" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <i class="bi bi-person-fill text-muted" style="font-size: 2.5rem;"></i>
                    @endif
                </div>
            </div>
            <div class="col">
                <input type="file" wire:model="form.profile_photo" class="form-control @error('form.profile_photo') is-invalid @enderror" accept="image/*">
                @error('form.profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div wire:loading wire:target="form.profile_photo" class="small text-muted mt-1">Uploading...</div>
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100" wire:loading.attr="disabled" wire:target="register">
            <span wire:loading.remove wire:target="register">Create Account</span>
            <span wire:loading wire:target="register">
                <span class="spinner-border spinner-border-sm me-1"></span> Creating account...
            </span>
        </button>

        <p class="text-center small text-muted mt-3 mb-0">
            Already have an account? <a href="{{ route('login') }}" class="text-brand text-decoration-none" wire:navigate>Sign in</a>
        </p>
    </form>
</div>
