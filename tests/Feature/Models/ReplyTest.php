<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
