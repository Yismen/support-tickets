<?php

namespace Dainsys\Support\Tests\Feature\Models;

use Dainsys\Support\Models\Rating;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Events\RatingCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function rating_model_interacts_with_db_table()
    {
        $data = Rating::factory()->make();

        $rating = Rating::create($data->toArray());

        $this->assertInstanceOf(Rating::class, $rating);
        $this->assertDatabaseHas(Rating::class, $data->only([
            'user_id', 'ticket_id', 'score', 'comment'
        ]));
    }

    /** @test */
    public function rating_model_belongs_to_one_ticket()
    {
        $rating = Rating::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $rating->ticket());
    }

    /** @test */
    public function rating_model_belongs_to_one_user()
    {
        $rating = Rating::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class, $rating->user());
    }

    /** @test */
    public function repy_model_emits_event_when_rating_is_created()
    {
        $rating = Rating::factory()->create();

        Event::assertDispatched(RatingCreatedEvent::class);
    }
}
