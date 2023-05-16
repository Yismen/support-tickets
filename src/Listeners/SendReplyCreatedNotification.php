<?php

namespace Dainsys\Support\Listeners;

use Illuminate\Support\Facades\Notification;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Notifications\ReplyCreatedNotification;

class SendReplyCreatedNotification
{
    public function handle(ReplyCreatedEvent $event)
    {
        Notification::send($event->reply->getNotifiables(), new ReplyCreatedNotification($event->reply));
    }
}
