<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Events\RatingCreatedEvent;
use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Models\Traits\BelongsToTicket;
use Dainsys\Support\Database\Factories\RatingFactory;

class Rating extends AbstractModel
{
    use BelongsToTicket;
    use BelongsToUser;

    protected $fillable = ['user_id', 'ticket_id', 'rating', 'comment'];

    protected $dispatchesEvents = [
        'created' => RatingCreatedEvent::class,
    ];

    protected static function newFactory(): RatingFactory
    {
        return RatingFactory::new();
    }
}
