<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Rating;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Mail\RatingCreatedMail;
use Dainsys\Support\Events\RatingCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendRatingCreatedMail;

class RatingCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            RatingCreatedEvent::class
        ]);

        $this->supportSuperAdminUser();

        $rating = Rating::factory()->create();

        Event::assertDispatched(RatingCreatedEvent::class);
        Event::assertListening(
            RatingCreatedEvent::class,
            SendRatingCreatedMail::class
        );
    }

    /** @test */
    public function when_rating_is_created_an_email_is_sent()
    {
        Mail::fake();

        $this->supportSuperAdminUser();

        $rating = Rating::factory()->create();

        Mail::assertQueued(RatingCreatedMail::class);
    }
}
