<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Roles</h1>
        @can('roles.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Role
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search roles...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Users</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr wire:key="role-{{ $role->id }}">
                                <td>
                                    {{ $role->name }}
                                    @if ($role->is_system)
                                        <span class="badge text-bg-light border ms-1">System</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $role->description ?: '-' }}</td>
                                <td>{{ $role->permissions->count() }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td class="text-end">
                                    @can('roles.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $role->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('roles.delete')
                                        @unless ($role->is_system)
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $role->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endunless
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No roles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $roles->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Role' : 'Add Role' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Description</label>
                                    <input type="text" wire:model.blur="form.description" class="form-control @error('form.description') is-invalid @enderror">
                                    @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Permissions</label>
                                    <div class="border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($permissions as $group => $items)
                                            <div class="mb-2">
                                                <div class="fw-semibold small text-muted mb-1">{{ $group }}</div>
                                                <div class="d-flex flex-wrap gap-3">
                                                    @foreach ($items as $permission)
                                                        <div class="form-check">
                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input"
                                                                id="perm-{{ $permission->id }}"
                                                                value="{{ $permission->id }}"
                                                                wire:model.blur="form.permission_ids"
                                                            >
                                                            <label class="form-check-label" for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
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

    @if ($deletingId)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Role</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this role? Users assigned to it will lose it.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
