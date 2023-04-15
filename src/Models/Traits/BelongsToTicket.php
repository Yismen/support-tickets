<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTicket
{
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
