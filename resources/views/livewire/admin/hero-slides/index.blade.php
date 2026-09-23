<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Hero Slides</h1>
        @can('hero-slides.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Slide
            </button>
        @endcan
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p class="text-muted small mb-0">
                Slides rotate automatically in the home page's hero banner. If no slide is active, the hero
                falls back to its default single-banner look. Order controls which slide shows first.
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
                            <th>Title</th>
                            <th>Style</th>
                            <th>Sort</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                            <tr wire:key="slide-{{ $slide->id }}">
                                <td>
                                    @if ($slide->imageUrl())
                                        <img src="{{ $slide->imageUrl() }}" alt="" class="rounded" style="width:56px;height:40px;object-fit:cover;">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:56px;height:40px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $slide->title }}</div>
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($slide->subtitle, 60) }}</div>
                                </td>
                                <td><span class="badge text-bg-light border">{{ $slide->display_mode->label() }}</span></td>
                                <td>{{ $slide->sort_order }}</td>
                                <td><span class="badge {{ $slide->status->badgeClass() }}">{{ $slide->status->label() }}</span></td>
                                <td class="text-end">
                                    @can('hero-slides.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $slide->id }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endcan
                                    @can('hero-slides.delete')
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $slide->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hero slides yet — the home page uses its default banner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $slides->links() }}</div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Hero Slide' : 'Add Hero Slide' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.blur="form.title" class="form-control @error('form.title') is-invalid @enderror">
                                    @error('form.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">Always used to identify the slide in this list — only shown on the site itself in "Text &amp; Button" mode.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Slide Style <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input type="radio" wire:model.live="form.display_mode" value="text_and_button" class="form-check-input" id="displayModeText">
                                            <label class="form-check-label" for="displayModeText">Text &amp; Button</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" wire:model.live="form.display_mode" value="image_only" class="form-check-input" id="displayModeImage">
                                            <label class="form-check-label" for="displayModeImage">Image Only</label>
                                        </div>
                                    </div>
                                    @error('form.display_mode') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                @if ($form->display_mode === 'text_and_button')
                                    <div class="col-12">
                                        <label class="form-label">Subtitle</label>
                                        <textarea wire:model.blur="form.subtitle" rows="2" class="form-control @error('form.subtitle') is-invalid @enderror"></textarea>
                                        @error('form.subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Button Text</label>
                                        <input type="text" wire:model.blur="form.button_text" class="form-control @error('form.button_text') is-invalid @enderror" placeholder="Get Started">
                                        @error('form.button_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Button Link</label>
                                        <input type="text" wire:model.blur="form.button_url" class="form-control @error('form.button_url') is-invalid @enderror" placeholder="/register">
                                        @error('form.button_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <label class="form-label">Sort Order <span class="text-danger">*</span></label>
                                    <input type="number" wire:model.blur="form.sort_order" class="form-control @error('form.sort_order') is-invalid @enderror">
                                    @error('form.sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select wire:model.blur="form.status" class="form-select @error('form.status') is-invalid @enderror">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('form.status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">
                                        {{ $form->display_mode === 'image_only' ? 'Image' : 'Background Image' }}
                                        @if ($form->display_mode === 'image_only') <span class="text-danger">*</span> @endif
                                    </label>
                                    <input type="file" wire:model="form.image" class="form-control @error('form.image') is-invalid @enderror" accept="image/*">
                                    @error('form.image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">
                                        @if ($form->display_mode === 'image_only')
                                            Required — (1897 * 560 px).
                                        @else
                                            Optional — leave blank to keep the theme gradient background.
                                        @endif
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
                        <h5 class="modal-title">Delete Hero Slide</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to delete this slide?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('deletingId', null)">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
