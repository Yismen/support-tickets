<?php

namespace Dainsys\Support\Events;

use Dainsys\Support\Models\Rating;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RatingCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Rating $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }
}
