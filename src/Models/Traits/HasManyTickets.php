<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTickets
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
