<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

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
        $reason = Reply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reason->ticket());
    }

    /** @test */
    public function replies_model_belongs_to_one_user()
    {
        $reason = Reply::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $reason->user());
    }
}
