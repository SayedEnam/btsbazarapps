<div>
    <h4 class="fw-bold text-brand mb-1">Reset your password</h4>
    <p class="text-muted mb-4">Choose a new password for your account.</p>

    <form wire:submit="resetPassword">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input
                type="email"
                id="email"
                wire:model="email"
                class="form-control @error('email') is-invalid @enderror"
                autocomplete="username"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input
                type="password"
                id="password"
                wire:model="password"
                class="form-control @error('password') is-invalid @enderror"
                autocomplete="new-password"
                autofocus
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input
                type="password"
                id="password_confirmation"
                wire:model="password_confirmation"
                class="form-control"
                autocomplete="new-password"
            >
        </div>

        <button type="submit" class="btn btn-brand w-100" wire:loading.attr="disabled">
            Reset Password
        </button>
    </form>
</div>
