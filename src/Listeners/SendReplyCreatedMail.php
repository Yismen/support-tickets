<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Reply;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\ReplyCreatedMail;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Services\RecipientsService;

class SendReplyCreatedMail
{
    protected Reply $reply;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(ReplyCreatedEvent $event)
    {
        $this->reply = $event->reply;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new ReplyCreatedMail($this->reply));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->reply->ticket)
            // ->superAdmins()
            ->owner()
            ->agent()
            ->allDepartmentAdmins()
            ->get();
    }
}
