<?php

namespace App\Notifications;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransformationAssignedNotification extends Notification
{
    use Queueable;

    protected Assessment $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    /**
     * Store this notification in the database.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Data stored in the notifications table.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'New Transformation Request',
            'message' => 'A Transformation Roadmap request has been assigned to you.',
            'assessment_id' => $this->assessment->id,
            'company_id' => $this->assessment->company_id,
            'company_name' => $this->assessment->company?->name,
            'type' => 'transformation_assigned',
        ];
    }
}