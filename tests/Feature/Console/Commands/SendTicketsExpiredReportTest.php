<?php

namespace Dainsys\Support\Tests\Feature\Console\Commands;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Dainsys\Support\Mail\TicketsExpiredMail;
use Dainsys\Support\Events\TicketCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Console\Commands\SendTicketsExpiredReport;

class SendTicketsExpiredReportTest extends TestCase implements ShouldQueue
{
    use RefreshDatabase;

    /** @test */
    public function command_is_schedulled_daily()
    {
        $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
            ->filter(function ($element) {
                return str($element->command)->contains('support:send-tickets-expired-report');
            })->first();

        $this->assertNotNull($addedToScheduler);
        $this->assertEquals('0 8 * * *', $addedToScheduler->expression);
    }

    /** @test */
    public function sends_report()
    {
        Mail::fake();
        Event::fake([
            TicketCreatedEvent::class
        ]);
        $ticket = Ticket::factory()->create();

        $recipient = $this->supportSuperAdminUser();
        $this->travelTo(now()->addDays(50));
        $this->artisan(SendTicketsExpiredReport::class);

        Mail::assertSent(TicketsExpiredMail::class, function ($mail) use ($ticket, $recipient) {
            return $mail->tickets->contains('id', $ticket->id)
                && $mail->to[0]['address'] === $recipient->email;
        });
    }

    /** @test */
    public function sends_reportonly_if_it_has_expired_tickets()
    {
        Mail::fake();
        Event::fake([
            TicketCreatedEvent::class
        ]);
        $ticket = Ticket::factory()->create();

        $recipient = $this->supportSuperAdminUser();
        $this->artisan(SendTicketsExpiredReport::class);

        Mail::assertNotSent(TicketsExpiredMail::class);
    }
}
