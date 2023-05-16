<?php

namespace Dainsys\Support\Tests\Feature\Events;

use Dainsys\Support\Models\Reply;
use Dainsys\Support\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dainsys\Support\Notifications\ReplyCreatedNotification;

class ReplyCreatedEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function reply_created_notify_agent_and_departtment_admin()
    {
        Notification::fake();

        $reply = Reply::factory()->create();

        Notification::assertSentTo($reply->getNotifiables(), ReplyCreatedNotification::class);
    }
}
