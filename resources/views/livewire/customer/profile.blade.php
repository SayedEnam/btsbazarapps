<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">My Profile</h1>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Personal Information</h2></div>
                <div class="card-body">
                    <form wire:submit="updateProfile">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Account</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mobile</label>
                                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" wire:model="username" class="form-control @error('username') is-invalid @enderror" autocomplete="username">
                                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" wire:model="new_profile_photo" class="form-control @error('new_profile_photo') is-invalid @enderror" accept="image/*">
                                @error('new_profile_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Personal Details</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Father's Name</label>
                                <input type="text" wire:model="father_name" class="form-control @error('father_name') is-invalid @enderror">
                                @error('father_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mother's Name</label>
                                <input type="text" wire:model="mother_name" class="form-control @error('mother_name') is-invalid @enderror">
                                @error('mother_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea wire:model="address" rows="2" class="form-control @error('address') is-invalid @enderror"></textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NID Number</label>
                                <input type="text" wire:model="nid_number" class="form-control @error('nid_number') is-invalid @enderror">
                                @error('nid_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Profession</label>
                                <input type="text" wire:model="profession" class="form-control @error('profession') is-invalid @enderror">
                                @error('profession') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" wire:model="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror">
                                @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select wire:model="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">Prefer not to say</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="updateProfile">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
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
                        <button type="submit" class="btn btn-brand w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
