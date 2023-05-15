<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Mail\TicketCompletedMail;
use Dainsys\Support\Events\TicketCompletedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendTicketCompletedMail;

class TicketCompletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCompletedEvent::class
        ]);

        $this->supportSuperAdmin();
        $ticket = Ticket::factory()->create();

        $ticket->complete();

        Event::assertDispatched(TicketCompletedEvent::class);
        Event::assertListening(
            TicketCompletedEvent::class,
            SendTicketCompletedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->supportSuperAdmin();
        $ticket = Ticket::factory()->assigned()->create();
        $ticket->complete();

        Mail::assertQueued(TicketCompletedMail::class);
    }
}
