<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationSubmitted extends Notification
{
    public function __construct(protected Application $application)
    {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New application received',
            'message' => "{$this->application->customer->user->name} applied for {$this->application->package->name}.",
            'application_id' => $this->application->id,
            'application_number' => $this->application->application_number,
        ];
    }
}
