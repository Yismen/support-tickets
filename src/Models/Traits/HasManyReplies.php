<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Reply;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyReplies
{
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }
}
