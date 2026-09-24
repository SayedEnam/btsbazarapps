<?php

namespace App\Livewire\Customer;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.customer')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function markNotificationRead(string $notificationId): void
    {
        Auth::user()->notifications()->where('id', $notificationId)->first()?->markAsRead();
    }

    public function render()
    {
        $user = Auth::user();
        $customer = $user->customer()->with(['referral.officer', 'applications' => fn ($q) => $q->latest('application_date')->with(['package', 'designation'])])->first();

        return view('livewire.customer.dashboard', [
            'customer' => $customer,
            'latestApplication' => $customer?->applications->first(),
            'notifications' => $user->unreadNotifications()->latest()->take(5)->get(),
        ]);
    }
}
