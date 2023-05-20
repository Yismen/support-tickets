<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Rating;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\RatingCreatedMail;
use Dainsys\Support\Events\RatingCreatedEvent;
use Dainsys\Support\Services\RecipientsService;

class SendRatingCreatedMail
{
    protected Rating $rating;
    protected RecipientsService $recipientsService;

    public function __construct()
    {
        $this->recipientsService = new RecipientsService();
    }

    public function handle(RatingCreatedEvent $event)
    {
        $this->rating = $event->rating;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new RatingCreatedMail($this->rating));
        }
    }

    protected function recipients()
    {
        return $this->recipientsService
            ->ofTicket($this->rating->ticket)
            ->superAdmins()
            ->owner()
            ->agent()
            ->allDepartmentAdmins()
            ->recipients();
    }
}
