<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Orchestra\Testbench\Factories\UserFactory;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Traits\EnsureDateNotWeekend;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;
    use EnsureDateNotWeekend;

    /** @test */
    public function tickets_model_interacts_with_db_table()
    {
        $data = Ticket::factory()->make();

        Ticket::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('tickets'), $data->only([
            'created_by',
            'department_id',
            'reason_id',
            'description',
            'assigned_to',
            'assigned_at',
            // 'expected_at',
            'completed_at',
            'status',
        ]));
    }

    /** @test */
    public function tickets_model_belongs_to_one_user()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->user());
    }

    /** @test */
    public function tickets_model_belongs_to_one_department()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->department());
    }

    /** @test */
    public function tickets_modelhas_many_replies()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $ticket->replies());
    }

    /** @test */
    public function tickets_model_belongs_to_one_reason()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->reason());
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_normal()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);
        
        $ticket->reason->update(['priority' => TicketPrioritiesEnum::Normal]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addDays(2)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_medium()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->reason->update(['priority' => TicketPrioritiesEnum::Medium]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addDay()),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_high()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->reason->update(['priority' => TicketPrioritiesEnum::High]);
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addMinutes(4 * 60)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_emergency()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->reason->update(['priority' => TicketPrioritiesEnum::Emergency]);
        $ticket->touch();
        
        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addMinutes(30)),
        ]);
    }

    /** @test */
    public function tickets_model_can_assign_an_agent()
    {
        $agent = UserFactory::new()->create();
        $department = Department::factory()->create();
        $ticket = Ticket::factory()->unassigned()->create(['department_id' => $department->id]);

        $ticket->assignTo($agent);

        $this->assertDatabaseHas(Ticket::class, [
            'assigned_to' => $agent->id,
            'assigned_at' => now(),
            'status' => TicketStatusesEnum::InProgress,
        ]);
    }

    /** @test */
    public function tickets_model_can_be_completed()
    {
        $agent = UserFactory::new()->create();
        $department = Department::factory()->create();
        $ticket = Ticket::factory()->assigned()->create(['department_id' => $department->id]);

        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'completed_at' => now(),
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_pending_when_ticket_is_created()
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatusesEnum::InProgress]);

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::Pending,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_pending_expired_when_expected_at_has_passed()
    {
        $ticket = Ticket::factory()->unassigned()->create(['status' => TicketStatusesEnum::InProgress]);

        $this->travelTo(now()->addDays(20));
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::PendingExpired,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_status()
    {
        $ticket = Ticket::factory()->create();

        $ticket->assignTo(UserFactory::new()->create());
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::InProgress,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_status_expired()
    {
        $ticket = Ticket::factory()->create();

        $ticket->assignTo(UserFactory::new()->create());
        $this->travelTo(now()->addDays(40));
        $ticket->touch();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::InProgressExpired,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_completed_compliant()
    {
        $ticket = Ticket::factory()->assigned()->create();

        // $this->travelTo(now()->addDays(40));
        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::Completed,
        ]);
    }

    /** @test */
    public function tickets_model_update_status_to_in_completed_expired()
    {
        $ticket = Ticket::factory()->assigned()->create();

        $this->travelTo(now()->addDays(40));
        $ticket->complete();

        $this->assertDatabaseHas(Ticket::class, [
            'status' => TicketStatusesEnum::CompletedExpired,
        ]);
    }
}
