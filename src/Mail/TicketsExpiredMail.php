<?php

namespace Dainsys\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TicketsExpiredMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function build()
    {
        return $this
            ->subject('Tickets Expired Report')
            ->priority(0)
            ->markdown('support::mail.tickets-expired')

        ;
    }
}
