<?php

namespace Dainsys\Support\Models\Traits;

use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
