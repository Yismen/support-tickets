<?php

namespace Dainsys\Support\Events;

use Dainsys\Support\Models\Reply;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ReplyCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Reply $reply;

    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }
}
