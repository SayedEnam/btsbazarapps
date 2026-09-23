<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Notifications</h1>
        <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="markAllAsRead">
            <i class="bi bi-check-all me-1"></i> Mark All Read
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @forelse ($notifications as $notification)
                <div class="px-3 py-3 border-bottom {{ $notification->read_at ? '' : 'bg-light' }}" wire:key="notif-{{ $notification->id }}">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                            <div class="text-muted small">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="text-muted" style="font-size: .75rem;">{{ $notification->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        @unless ($notification->read_at)
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="markAsRead('{{ $notification->id }}')">
                                Mark Read
                            </button>
                        @endunless
                    </div>
                </div>
            @empty
                <p class="text-center text-muted py-5 mb-0">No notifications yet.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-3">{{ $notifications->links() }}</div>
</div>
