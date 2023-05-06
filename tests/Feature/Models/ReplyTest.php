<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Database\Factories\DepartmentRoleFactory;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function replies_model_interacts_with_db_table()
    {
        $data = Reply::factory()->make();

        Reply::create($data->toArray());

        $this->assertDatabaseHas(supportTableName('replies'), $data->only([
            'user_id', 'ticket_id', 'content'
        ]));
    }

    /** @test */
    public function replies_model_belongs_to_one_ticket()
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reply->ticket());
    }

    /** @test */
    public function replies_model_belongs_to_one_user()
    {
        $reply = Reply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reply->user());
    }

    /** @test */
    public function repy_model_emits_event_when_reply_is_created()
    {
        $reply = Reply::factory()->create();

        Event::assertDispatched(ReplyCreatedEvent::class);
    }

    /** @test */
    public function reply_model_returns_list_of_notifiable_users()
    {
        $reply = new Reply();

        $this->assertEquals([], $reply->getNotifiables());
    }

    /** @test */
    public function reply_model_returns_notifiables_does_not_contain_same_sender()
    {
        Event::fake();
        $ticket = Ticket::factory()->create();
        $reply = Reply::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $ticket->created_by]);
        $this->assertEquals([], $reply->getNotifiables());
    }

    /** @test */
    public function reply_model_returns_notifiables_contains_department_admin()
    {
        Event::fake();
        $ticket = Ticket::factory()->create();
        $reply = Reply::factory()->create(['ticket_id' => $ticket->id]);
        $admin = DepartmentRole::factory()->admin()->create(['department_id' => $ticket->department_id]);
        
        $this->assertEquals($admin->id, $reply->getNotifiables()[0]->id);
    }

    /** @test */
    public function reply_model_returns_notifiables_contains_ticket_agent()
    {
        Event::fake();
        $ticket = Ticket::factory()->create();
        $agent = DepartmentRole::factory()->admin()->create(['department_id' => $ticket->department_id]);
        $ticket->assignTo($agent);
        $reply = Reply::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertEquals($agent->id, $reply->getNotifiables()[0]->id);
    }


}
