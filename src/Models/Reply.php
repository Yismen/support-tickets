<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Models\Traits\BelongsToTicket;
use Dainsys\Support\Database\Factories\ReplyFactory;

class Reply extends AbstractModel
{
    use BelongsToTicket;
    use BelongsToUser;

    protected $fillable = ['user_id', 'ticket_id', 'content'];

    protected static function newFactory(): ReplyFactory
    {
        return ReplyFactory::new();
    }

    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }
}
