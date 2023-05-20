<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\TicketCreatedMail;
use Dainsys\Support\Events\TicketCreatedEvent;
use Dainsys\Support\Services\RecipientsService;

class SendTicketCreatedMail
{
    protected Ticket $ticket;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketCreatedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCreatedMail($this->ticket));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->allDepartmentAdmins()
            ->allDepartmentAgents()
            ->recipients();
    }
}
