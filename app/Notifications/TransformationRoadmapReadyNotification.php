<?php

namespace App\Notifications;

use App\Models\Assessment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransformationRoadmapReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Assessment $assessment
    ) {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $companyName = $this->assessment->company?->name ?? 'your organization';

        return (new MailMessage)
            ->subject('Your YARA Transformation Roadmap is ready')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line(
                "Your expert-reviewed AI Transformation Roadmap for {$companyName} has been finalized."
            )
            ->line(
                'Your roadmap is now available securely in your YARA workspace.'
            )
            ->action(
                'View Transformation Roadmap',
                route('assessment.results', $this->assessment->id)
            )
            ->line(
                'Thank you for using YARA by Yellomind Consulting.'
            );
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}