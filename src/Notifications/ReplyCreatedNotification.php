<?php

namespace Dainsys\Support\Notifications;

use Illuminate\Bus\Queueable;
use Dainsys\Support\Models\Reply;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Reply $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                ->greeting('Hello!')
                ->line("User {$this->reply->user->name} replied on ticket '{$this->reply->ticket->id}' with the following message:")
                ->line($this->reply->content)
                ->action('View Tickets', route('support.my_tickets'))
                ->line('Thanks!');
    }
}
