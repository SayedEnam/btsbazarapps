<?php

namespace App\Livewire\Admin\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Notifications')]
class Index extends Component
{
    use WithPagination;

    public function markAsRead(string $notificationId): void
    {
        Auth::user()->notifications()->where('id', $notificationId)->first()?->markAsRead();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();

        $this->dispatch('notify', type: 'success', message: 'All notifications marked as read.');
    }

    public function render()
    {
        return view('livewire.admin.notifications.index', [
            'notifications' => Auth::user()->notifications()->latest()->paginate(20),
        ]);
    }
}
