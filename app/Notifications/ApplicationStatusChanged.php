<?php

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    public function __construct(protected Application $application, protected ApplicationStatus $newStatus)
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
            'title' => match ($this->newStatus) {
                ApplicationStatus::Approved => 'Your application has been approved',
                ApplicationStatus::Rejected => 'Your application has been rejected',
                default => 'Your application status has changed',
            },
            'message' => "Application {$this->application->application_number} for {$this->application->package->name} is now {$this->newStatus->label()}.",
            'application_id' => $this->application->id,
            'application_number' => $this->application->application_number,
            'status' => $this->newStatus->value,
        ];
    }
}
