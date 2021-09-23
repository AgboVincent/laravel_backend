<?php

namespace App\Notifications\Claim\Settlement;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Accepted extends Notification
{
    use Queueable;

    private Claim $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(User $notifiable): array
    {
        return [];
    }
}
