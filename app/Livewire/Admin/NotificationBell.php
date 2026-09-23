<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    #[On('notifications-updated')]
    public function refresh(): void
    {
        // Re-render is enough — render() below re-queries fresh data.
    }

    public function markAsRead(string $notificationId): void
    {
        Auth::user()->notifications()->where('id', $notificationId)->first()?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.admin.notification-bell', [
            'notifications' => $user->notifications()->latest()->take(8)->get(),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }
}
