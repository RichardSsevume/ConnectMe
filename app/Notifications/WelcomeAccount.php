<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeAccount extends Notification
{
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $role = $notifiable->isEmployer() ? 'employer' : 'skilled youth';
        $next = $notifiable->isEmployer()
            ? 'Complete your company profile, then post your first vacancy and start finding skilled people.'
            : 'Complete your profile, upload your documents and start applying for vacancies.';

        return (new MailMessage)
            ->subject('Welcome to Connect Me - your account is ready')
            ->greeting('Hello ' . $notifiable->displayName() . ',')
            ->line("Your Connect Me {$role} account has been created successfully.")
            ->line($next)
            ->action('Go to my dashboard', route('dashboard'))
            ->line('Connecting Skills to Opportunities.');
    }
}
