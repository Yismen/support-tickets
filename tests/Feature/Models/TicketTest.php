<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

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
            'expected_at',
            'status',
            'priority',
            'completed_at',
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
}
