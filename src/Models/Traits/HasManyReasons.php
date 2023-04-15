<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Reason;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyReasons
{
    public function reasons(): HasMany
    {
        return $this->hasMany(Reason::class);
    }
}
