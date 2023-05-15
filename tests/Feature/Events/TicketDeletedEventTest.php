<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Mail\TicketDeletedMail;
use Dainsys\Support\Events\TicketDeletedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendTicketDeletedMail;

class TicketDeletedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketDeletedEvent::class
        ]);
        $this->supportSuperAdmin();

        $ticket = Ticket::factory()->create();

        $ticket->delete();

        Event::assertDispatched(TicketDeletedEvent::class);
        Event::assertListening(
            TicketDeletedEvent::class,
            SendTicketDeletedMail::class
        );
    }

    /** @test */
    public function when_ticket_is_created_an_email_is_sent()
    {
        Mail::fake();

        $ticket = Ticket::factory()->create();
        $ticket->delete();

        Mail::assertQueued(TicketDeletedMail::class);
    }
}
