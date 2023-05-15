<?php

namespace Dainsys\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Dainsys\Support\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketCompletedMail extends Mailable implements ShouldQueue
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
            ->subject("Ticket #{$this->ticket->reference} Completed")
            ->priority($this->ticket->mail_priority)
            ->markdown('support::mail.ticket-completed', [
                'user' => $this->ticket->audits()->latest()->first()?->user
            ])

        ;
    }
}
