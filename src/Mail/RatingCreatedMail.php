<?php

namespace Dainsys\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Dainsys\Support\Models\Rating;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RatingCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public Rating $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    public function build()
    {
        $ticket = $this->rating->ticket;

        return $this
            ->subject("Ticket #{$ticket->reference} Has Been Rated")
            ->priority($ticket->mail_priority)
            ->markdown('support::mail.rating-created', [
                'user' => $this->rating->user
            ])

        ;
    }
}
