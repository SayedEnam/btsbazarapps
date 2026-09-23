<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">My Profile</h1>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h2 class="h6 mb-0">Profile Information</h2>
                </div>
                <div class="card-body">
                    <form wire:submit="updateProfile">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model.blur="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" wire:model.blur="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" wire:model.blur="username" class="form-control @error('username') is-invalid @enderror" autocomplete="username">
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" wire:model.blur="phone" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-brand">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h2 class="h6 mb-0">Change Password</h2>
                </div>
                <div class="card-body">
                    <form wire:submit="updatePassword">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" wire:model.blur="passwordForm.current_password" class="form-control @error('passwordForm.current_password') is-invalid @enderror" autocomplete="current-password">
                            @error('passwordForm.current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" wire:model.blur="passwordForm.password" class="form-control @error('passwordForm.password') is-invalid @enderror" autocomplete="new-password">
                            @error('passwordForm.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" wire:model.blur="passwordForm.password_confirmation" class="form-control" autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-brand">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
