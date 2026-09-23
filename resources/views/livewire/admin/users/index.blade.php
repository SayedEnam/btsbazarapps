<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Users</h1>
        @can('users.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add User
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input
                        type="search"
                        wire:model.live.debounce.400ms="search"
                        class="form-control"
                        placeholder="Search name, username, email or phone..."
                    >
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10 / page</option>
                        <option value="25">25 / page</option>
                        <option value="50">50 / page</option>
                        <option value="100">100 / page</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Roles</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->username ?: '-' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?: '-' }}</td>
                                <td>
                                    @forelse ($user->roles as $role)
                                        <span class="badge text-bg-light border me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                </td>
                                <td><span class="badge {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('users.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $user->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('delete', $user)
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $user->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    {{-- Create / Edit modal --}}
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:keydown.escape="$set('showModal', false)">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit User' : 'Add User' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" wire:model.blur="form.email" class="form-control @error('form.email') is-invalid @enderror">
                                    @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.username" class="form-control @error('form.username') is-invalid @enderror" autocomplete="username">
                                    @error('form.username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" wire:model.blur="form.phone" class="form-control @error('form.phone') is-invalid @enderror">
                                    @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Password
                                        @unless ($editingId) <span class="text-danger">*</span> @endunless
                                        @if ($editingId) <span class="text-muted small">(leave blank to keep current)</span> @endif
                                    </label>
                                    <input type="password" wire:model.blur="form.password" class="form-control @error('form.password') is-invalid @enderror" autocomplete="new-password">
                                    @error('form.password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Roles</label>
                                    <div class="d-flex flex-wrap gap-3 border rounded p-2">
                                        @foreach ($roles as $role)
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="role-{{ $role->id }}"
                                                    value="{{ $role->id }}"
                                                    wire:model.blur="form.role_ids"
                                                >
                                                <label class="form-check-label" for="role-{{ $role->id }}">{{ $role->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('showModal', false)">Cancel</button>
                            <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete confirmation modal --}}
    @if ($deletingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete User</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this user? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
