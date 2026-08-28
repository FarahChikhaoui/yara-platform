<?php

namespace App\Notifications;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewTransformationRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Assessment $assessment
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Transformation Request',

            'message' =>
                'A client has purchased a Transformation Roadmap and the request is ready for consultant assignment.',

            'assessment_id' => $this->assessment->id,

            'company_id' => $this->assessment->company_id,

            'company_name' =>
                $this->assessment->company?->name,

            'type' => 'transformation_paid',
        ];
    }
}