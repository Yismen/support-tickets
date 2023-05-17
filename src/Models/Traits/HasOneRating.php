<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Rating;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasOneRating
{
    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }
}
