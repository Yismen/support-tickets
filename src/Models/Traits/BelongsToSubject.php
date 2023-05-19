<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Subject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSubject
{
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
