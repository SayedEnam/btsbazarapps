<div class="dropdown">
    <button class="btn btn-sm btn-light border position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        @if ($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .6rem;">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end shadow-sm p-0" style="width: 320px;">
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
            <h6 class="dropdown-header p-0 m-0">Notifications</h6>
            @if ($unreadCount > 0)
                <button type="button" class="btn btn-link btn-sm p-0" wire:click="markAllAsRead">Mark all read</button>
            @endif
        </div>

        <div style="max-height: 320px; overflow-y: auto;">
            @forelse ($notifications as $notification)
                <div class="px-3 py-2 border-bottom small {{ $notification->read_at ? '' : 'bg-light' }}" wire:key="notif-{{ $notification->id }}">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                            <div class="text-muted">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="text-muted" style="font-size: .7rem;">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        @unless ($notification->read_at)
                            <button type="button" class="btn btn-sm btn-link p-0" wire:click="markAsRead('{{ $notification->id }}')" title="Mark as read">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        @endunless
                    </div>
                </div>
            @empty
                <p class="text-center text-muted small py-3 mb-0">No notifications yet.</p>
            @endforelse
        </div>
    </div>
</div>
