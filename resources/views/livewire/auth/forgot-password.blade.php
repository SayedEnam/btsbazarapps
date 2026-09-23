<div>
    <h4 class="fw-bold text-brand mb-1">Forgot your password?</h4>
    <p class="text-muted mb-4">Enter your email and we'll send you a link to reset it.</p>

    @if ($status)
        <div class="alert alert-success">{{ $status }}</div>
    @endif

    <form wire:submit="sendResetLink">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input
                type="email"
                id="email"
                wire:model="email"
                class="form-control @error('email') is-invalid @enderror"
                autocomplete="username"
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-brand w-100" wire:loading.attr="disabled">
            Send Password Reset Link
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small text-brand text-decoration-none" wire:navigate>Back to login</a>
        </div>
    </form>
</div>
