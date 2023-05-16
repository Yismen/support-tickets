<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Mail\TicketCreatedMail;
use Dainsys\Support\Events\TicketCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendTicketCreatedMail;

class TicketCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            TicketCreatedEvent::class
        ]);

        $ticket = Ticket::factory()->create();

        Event::assertDispatched(TicketCreatedEvent::class);
        Event::assertListening(
            TicketCreatedEvent::class,
            SendTicketCreatedMail::class
        );
    }

    /** @test */
    public function email_is_sent()
    {
        Mail::fake();
        $superAdmin = $this->supportSuperAdmin();
        $department = Department::factory()->create();
        $department_admin = $this->departmentAdmin($department);

        $ticket = Ticket::factory()->create(['department_id' => $department->id]);

        Mail::assertQueued(TicketCreatedMail::class);
    }
}
