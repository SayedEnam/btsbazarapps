<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Departments</h1>
        @can('departments.create')
            <button type="button" class="btn btn-brand" wire:click="create">
                <i class="bi bi-plus-lg me-1"></i> Add Department
            </button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-6 col-lg-4">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search departments...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Officers</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $department)
                            @if ($searching)
                                <tr wire:key="department-{{ $department->id }}">
                                    <td>
                                        {{ $department->name }}
                                        @if ($department->parent)
                                            <span class="text-muted small">(Sub-department of {{ $department->parent->name }})</span>
                                        @endif
                                    </td>
                                    <td>{{ $department->officers_count }}</td>
                                    <td class="text-end">
                                        @can('departments.edit')
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $department->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endcan
                                        @can('departments.delete')
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $department->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @else
                                <tr wire:key="department-{{ $department->id }}">
                                    <td class="fw-semibold">{{ $department->name }}</td>
                                    <td>{{ $department->officers_count }}</td>
                                    <td class="text-end">
                                        @can('departments.create')
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="createSubDepartment({{ $department->id }})" title="Add Sub-department">
                                                <i class="bi bi-plus-lg"></i> Sub
                                            </button>
                                        @endcan
                                        @can('departments.edit')
                                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $department->id }})">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endcan
                                        @can('departments.delete')
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $department->id }})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                                @foreach ($department->children as $child)
                                    <tr wire:key="department-{{ $child->id }}">
                                        <td class="ps-4 text-muted">
                                            <i class="bi bi-arrow-return-right me-1"></i>{{ $child->name }}
                                        </td>
                                        <td>{{ $child->officers_count }}</td>
                                        <td class="text-end">
                                            @can('departments.edit')
                                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="edit({{ $child->id }})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @endcan
                                            @can('departments.delete')
                                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $child->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No departments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($showModal)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form wire:submit="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Edit Department' : 'Add Department' }}</h5>
                            <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" wire:model.blur="form.name" class="form-control @error('form.name') is-invalid @enderror">
                                @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Parent Department</label>
                                <select wire:model.blur="form.parent_id" class="form-select @error('form.parent_id') is-invalid @enderror">
                                    <option value="">-- None (top-level department) --</option>
                                    @foreach ($parentOptions as $option)
                                        @unless ($option->id === $editingId)
                                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                                        @endunless
                                    @endforeach
                                </select>
                                @error('form.parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="form-text">Leave unset for a top-level department, or choose one to make this a sub-department of it.</div>
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
                        <h5 class="modal-title">Delete Department</h5>
                        <button type="button" class="btn-close" wire:click="$set('deletingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure? Officers assigned to this department will simply have it unset, and any
                        sub-departments underneath it become top-level departments of their own.
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
