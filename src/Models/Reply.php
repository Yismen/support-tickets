<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Models\Traits\BelongsToTicket;
use Dainsys\Support\Database\Factories\ReplyFactory;
use Dainsys\Support\Models\Traits\HasShortDescription;

class Reply extends AbstractModel
{
    use BelongsToTicket;
    use BelongsToUser;
    use HasShortDescription;

    protected $fillable = ['user_id', 'ticket_id', 'content'];

    protected $dispatchesEvents = [
        'created' => ReplyCreatedEvent::class,
    ];

    protected static function newFactory(): ReplyFactory
    {
        return ReplyFactory::new();
    }
}
