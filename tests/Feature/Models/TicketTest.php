<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Dainsys\Support\Models\Department;
use Orchestra\Testbench\Factories\UserFactory;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Enums\TicketProgressesEnum;
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
            'priority',
            'completed_at',
            'progress',
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
    public function tickets_model_belongs_to_one_reason()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $ticket->reason());
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_normal()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->update(['priority' => TicketPrioritiesEnum::Normal]);

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addDays(2)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_medium()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->update(['priority' => TicketPrioritiesEnum::Medium]);

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addDay()),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_high()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->update(['priority' => TicketPrioritiesEnum::High]);

        $this->assertDatabaseHas(Ticket::class, [
            'expected_at' => $this->ensureNotWeekend(now()->addMinutes(4 * 60)),
        ]);
    }

    /** @test */
    public function tickets_model_updates_expected_at_when_priority_is_emergency()
    {
        $ticket = Ticket::factory()->create(['created_at' => now()]);

        $ticket->update(['priority' => TicketPrioritiesEnum::Emergency]);

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
            'progress' => TicketProgressesEnum::InProgress,
        ]);
    }

    /** @test */
    // public function tickets_model_can_be_completed()
    // {
    //     $agent = UserFactory::new()->create();
    //     $department = Department::factory()->create();
    //     $ticket = Ticket::factory()->assigned()->create(['department_id' => $department->id]);

    //     $ticket->complete();

    //     $this->assertDatabaseHas(Ticket::class, [
    //         'progress' => TicketProgressesEnum::Completed,
    //         'completed_at' => now(),
    //     ]);
    // }

    // case Pending = 'pending';
    // created

    // case Expired = 'expired';
    // Not completed yet, time passed,
    // case InProgress = 'assigned';
    // Assigned, not completed yet, still on time
    // case OnTime = 'on_time';
    // Completed on time
    // case Late = 'late';
    // completed after timeframe

    /** @test */
    public function tickets_model_update_progress_to_pending_when_ticket_is_created()
    {
        $ticket = Ticket::factory()->create(['progress' => TicketProgressesEnum::InProgress, 'assigned_to' => 20]);

        $this->assertDatabaseHas(Ticket::class, [
            'progress' => TicketProgressesEnum::Pending,
            'assigned_to' => null,
        ]);
    }
}
