<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Activity Logs</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control" placeholder="Search description...">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model.live="moduleFilter" class="form-select">
                        <option value="">All Modules</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}">{{ $module }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3 col-md-2" style="max-width: 150px;">
                    <input type="date" wire:model.live="dateFrom" class="form-control" title="From date">
                </div>
                <div class="col-3 col-md-2" style="max-width: 150px;">
                    <input type="date" wire:model.live="dateTo" class="form-control" title="To date">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>User</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr wire:key="log-{{ $log->id }}">
                                <td class="text-muted small">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td><span class="badge text-bg-light border">{{ $log->module }}</span></td>
                                <td>{{ $log->description }}</td>
                                <td class="text-end">
                                    @if ($log->old_values || $log->new_values)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="view({{ $log->id }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No activity recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $logs->links() }}</div>
        </div>
    </div>

    @if ($viewing)
        <div class="modal d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Activity Details</h5>
                        <button type="button" class="btn-close" wire:click="$set('viewingId', null)"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $viewing->description }}</p>
                        <p class="text-muted small mb-3">
                            IP: {{ $viewing->ip_address ?: '-' }}<br>
                            Agent: {{ $viewing->user_agent ?: '-' }}
                        </p>
                        @if ($viewing->old_values)
                            <h6 class="small text-uppercase text-muted fw-bold">Before</h6>
                            <pre class="small bg-light p-2 rounded">{{ json_encode($viewing->old_values, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                        @if ($viewing->new_values)
                            <h6 class="small text-uppercase text-muted fw-bold">After</h6>
                            <pre class="small bg-light p-2 rounded">{{ json_encode($viewing->new_values, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('viewingId', null)">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
