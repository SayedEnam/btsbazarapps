<div>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Pages</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-muted small mb-3">
                These are the public site's fixed content pages. Edit the copy below — changes go live
                immediately, no code changes needed.
            </p>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Last Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pages as $page)
                            <tr wire:key="page-{{ $page->id }}">
                                <td>{{ $page->title }}</td>
                                <td><code>/{{ $page->slug }}</code></td>
                                <td class="text-muted">{{ $page->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    @can('pages.edit')
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $page->id }})">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Page</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" wire:model.blur="title" class="form-control @error('title') is-invalid @enderror">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <input type="text" wire:model.blur="meta_description" class="form-control @error('meta_description') is-invalid @enderror">
                                @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Content <span class="text-danger">*</span></label>
                                <livewire:quill-text-editor wire:model.live="content" theme="snow" :key="'page-content-'.$editingId" />
                                @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
</div>
