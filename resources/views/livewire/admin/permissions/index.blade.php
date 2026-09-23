<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Permissions</h1>
        @can('permissions.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Permission
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search permissions...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Group</th>
                            <th>Roles</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                            <tr wire:key="permission-{{ $permission->id }}">
                                <td>{{ $permission->name }}</td>
                                <td><code>{{ $permission->slug }}</code></td>
                                <td><span class="badge text-bg-light border">{{ $permission->group }}</span></td>
                                <td>{{ $permission->roles_count }}</td>
                                <td class="text-end">
                                    @can('permissions.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $permission->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('permissions.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $permission->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No permissions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $permissions->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Permission' : 'Add Permission' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model.blur="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Group <span class="text-danger">*</span></label>
                                <input type="text" wire:model.blur="form.group" class="form-control @error('form.group') is-invalid @enderror" placeholder="e.g. Users, Officers, Packages">
                                @error('form.group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea wire:model.blur="form.description" class="form-control @error('form.description') is-invalid @enderror" rows="2"></textarea>
                                @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5 class="modal-title">Delete Permission</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this permission? It will be revoked from every role that has it.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
