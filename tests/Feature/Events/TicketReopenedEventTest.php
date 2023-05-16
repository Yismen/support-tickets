<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Mail\TicketReopenedMail;
use Dainsys\Support\Events\TicketReopenedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendTicketReopenedMail;

class TicketReopenedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketReopenedEvent::class
        ]);

        $this->supportSuperAdmin();
        $ticket = Ticket::factory()->create();

        $ticket->reopen();

        Event::assertDispatched(TicketReopenedEvent::class);
        Event::assertListening(
            TicketReopenedEvent::class,
            SendTicketReopenedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->supportSuperAdmin();
        $ticket = Ticket::factory()->assigned()->create();
        $ticket->reopen();

        Mail::assertQueued(TicketReopenedMail::class);
    }
}
