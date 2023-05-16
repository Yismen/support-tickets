<?php

namespace Dainsys\Support\Tests\Feature\Console\Commands;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Console\Commands\UpdateTicketStatus;

class UpdateTicketStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_is_schedulled_for_evey_thirty_minutes()
    {
        $addedToScheduler = collect(app()->make(\Illuminate\Console\Scheduling\Schedule::class)->events())
            ->filter(function ($element) {
                return str($element->command)->contains('support:update-ticket-status');
            })->first();

        $this->assertNotNull($addedToScheduler);
        $this->assertEquals('0,30 * * * *', $addedToScheduler->expression);
    }

    /** @test */
    public function update_tickets_status()
    {
        $ticket = Ticket::factory()->create();

        $this->travelTo(now()->addDays(50));
        $this->artisan(UpdateTicketStatus::class);

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::PendingExpired
        ]);
    }

    /** @test */
    public function update_tickets_only_updates_ticket_2_tickets()
    {
        $ticket_1 = Ticket::factory()->completed()->create();
        $ticket_2 = Ticket::factory()->create();

        $this->travelTo(now()->addDays(50));
        $this->artisan(UpdateTicketStatus::class);

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket_1->id,
            'status' => TicketStatusesEnum::Completed
        ]);

        $this->assertDatabaseHas(Ticket::class, [
            'id' => $ticket_2->id,
            'status' => TicketStatusesEnum::PendingExpired
        ]);
    }
}
