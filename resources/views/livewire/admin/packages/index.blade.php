<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Packages</h1>
        @can('packages.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Package
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search name or code...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Price</th>
                            <th>Applications</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr wire:key="package-{{ $package->id }}">
                                <td>
                                    @if ($package->imageUrl())
                                        <img src="{{ $package->imageUrl() }}" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $package->name }}</div>
                                    <div class="text-muted small">{{ $package->duration ?: '-' }}</div>
                                </td>
                                <td><code>{{ $package->code }}</code></td>
                                <td>৳{{ number_format((float) $package->price, 2) }}</td>
                                <td>{{ $package->applications_count }}</td>
                                <td>{{ $package->sort_order }}</td>
                                <td><span class="badge {{ $package->status->badgeClass() }}">{{ $package->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('packages.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $package->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('packages.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $package->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No packages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $packages->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Package' : 'Add Package' }}</h5>
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
                                    <label class="form-label">Code <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.code" class="form-control @error('form.code') is-invalid @enderror" placeholder="PKG-0001">
                                    @error('form.code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @unless ($editingId)
                                        <div class="form-text">Auto-generated — you can change it if needed.</div>
                                    @endunless
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" wire:model.blur="form.price" class="form-control @error('form.price') is-invalid @enderror">
                                    @error('form.price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Duration</label>
                                    <input type="text" wire:model.blur="form.duration" class="form-control @error('form.duration') is-invalid @enderror" placeholder="Monthly">
                                    @error('form.duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                                    <input type="number" wire:model.blur="form.sort_order" class="form-control @error('form.sort_order') is-invalid @enderror">
                                    @error('form.sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Image</label>
                                    <input type="file" wire:model="form.image" class="form-control @error('form.image') is-invalid @enderror" accept="image/*">
                                    @error('form.image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea wire:model.blur="form.description" rows="3" class="form-control @error('form.description') is-invalid @enderror"></textarea>
                                    @error('form.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5 class="modal-title">Delete Package</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure? Existing applications keep their own price snapshot and are not affected.</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
