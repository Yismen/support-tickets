<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Mail\TicketCompletedMail;
use Dainsys\Support\Services\RecipientsService;
use Dainsys\Support\Events\TicketCompletedEvent;

class SendTicketCompletedMail
{
    protected Ticket $ticket;
    protected string $comment;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(TicketCompletedEvent $event)
    {
        $this->ticket = $event->ticket;
        $this->comment = $event->comment;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCompletedMail($this->ticket, $this->comment));
        }
    }

    protected function recipients(): Collection
    {
        return $this->recipientsService
            ->ofTicket($this->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->departmentAdmins()
            ->get();
    }
}
