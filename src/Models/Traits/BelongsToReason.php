<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Reason;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToReason
{
    public function reason(): BelongsTo
    {
        return $this->belongsTo(Reason::class);
    }
}
