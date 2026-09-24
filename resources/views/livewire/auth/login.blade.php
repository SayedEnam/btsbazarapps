<div>
    <h4 class="fw-bold text-brand mb-1">Welcome back</h4>
    <p class="text-muted mb-4">Sign in to your Monthly Bazar account.</p>

    <form wire:submit="login">
        <div class="mb-3">
            <label for="login" class="form-label">Email or Username</label>
            <input
                type="text"
                id="login"
                wire:model="form.login"
                class="form-control @error('form.login') is-invalid @enderror"
                autocomplete="username"
                autofocus
            >
            @error('form.login')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                type="password"
                id="password"
                wire:model="form.password"
                class="form-control @error('form.password') is-invalid @enderror"
                autocomplete="current-password"
            >
            @error('form.password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input type="checkbox" wire:model="form.remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="small text-brand text-decoration-none" wire:navigate>Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-brand w-100" wire:loading.attr="disabled" wire:target="login">
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login">
                <span class="spinner-border spinner-border-sm me-1"></span> Signing in...
            </span>
        </button>

        <div class="text-center mt-3">
            <p class="text-center small text-muted mt-3 mb-0">Not a member yet?
            <a href="{{ route('register') }}" wire:navigate class="text-brand text-decoration-none">Create a New Account</a>
            </p>
        </div>
    </form>
</div>
