<?php

namespace Dainsys\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Dainsys\Support\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketReopenedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function build()
    {
        return $this
            ->subject("Ticket #{$this->ticket->reference} Reopened")
            ->priority($this->ticket->mail_priority)
            ->markdown('support::mail.ticket-reopened', [
                'user' => $this->ticket->audits()->latest()->first()?->user
            ])

        ;
    }
}
