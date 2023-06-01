<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Dainsys\Support\Mail\ReplyCreatedMail;
use Dainsys\Support\Events\ReplyCreatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Listeners\SendReplyCreatedMail;

class ReplyCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function event_is_dispatched()
    {
        Event::fake([
            ReplyCreatedEvent::class
        ]);

        $this->supportSuperAdminUser();

        $reply = Reply::factory()->create();

        Event::assertDispatched(ReplyCreatedEvent::class);
        Event::assertListening(
            ReplyCreatedEvent::class,
            SendReplyCreatedMail::class
        );
    }

    /** @test */
    public function when_reply_is_created_an_email_is_sent()
    {
        Mail::fake();

        $reply = Reply::factory()->create();

        Mail::assertQueued(ReplyCreatedMail::class);
    }
}
