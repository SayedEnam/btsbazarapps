<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Testimonials</h1>
        @can('testimonials.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Testimonial
            </button>
        @endcan
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p class="text-muted small mb-0">
                Shown in a slider on the home page, right after the packages section. Order controls which
                testimonial shows first.
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Customer</th>
                            <th>Quote</th>
                            <th>Rating</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                            <tr wire:key="testimonial-{{ $testimonial->id }}">
                                <td>
                                    @if ($testimonial->photoUrl())
                                        <img src="{{ $testimonial->photoUrl() }}" alt="" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $testimonial->customer_name }}</div>
                                    <div class="text-muted small">{{ $testimonial->role_or_company ?: '-' }}</div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($testimonial->quote, 60) }}</td>
                                <td>{{ $testimonial->rating ? str_repeat('★', $testimonial->rating) : '-' }}</td>
                                <td>{{ $testimonial->sort_order }}</td>
                                <td><span class="badge {{ $testimonial->status->badgeClass() }}">{{ $testimonial->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('testimonials.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $testimonial->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('testimonials.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $testimonial->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No testimonials yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $testimonials->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Testimonial' : 'Add Testimonial' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.customer_name" class="form-control @error('form.customer_name') is-invalid @enderror">
                                    @error('form.customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Role / Company</label>
                                    <input type="text" wire:model.blur="form.role_or_company" class="form-control @error('form.role_or_company') is-invalid @enderror" placeholder="Member since 2025">
                                    @error('form.role_or_company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quote <span class="text-danger">*</span></label>
                                    <textarea wire:model.blur="form.quote" rows="3" class="form-control @error('form.quote') is-invalid @enderror"></textarea>
                                    @error('form.quote') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rating</label>
                                    <select wire:model.blur="form.rating" class="form-select @error('form.rating') is-invalid @enderror">
                                        <option value="">None</option>
                                        <option value="1">★☆☆☆☆</option>
                                        <option value="2">★★☆☆☆</option>
                                        <option value="3">★★★☆☆</option>
                                        <option value="4">★★★★☆</option>
                                        <option value="5">★★★★★</option>
                                    </select>
                                    @error('form.rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                                    <input type="number" wire:model.blur="form.sort_order" class="form-control @error('form.sort_order') is-invalid @enderror">
                                    @error('form.sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Photo</label>
                                    <input type="file" wire:model="form.photo" class="form-control @error('form.photo') is-invalid @enderror" accept="image/*">
                                    @error('form.photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5 class="modal-title">Delete Testimonial</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this testimonial?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
