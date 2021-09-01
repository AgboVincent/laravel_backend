<?php

namespace App\Notifications\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAccountCreation extends Notification
{
    use Queueable;

    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Account Created')
            ->greeting('Hello ' . $notifiable->first_name)
            ->line('An account has been created for you with the password: ' . $this->password)
            ->line('Thank you for using our application!');
    }
}
