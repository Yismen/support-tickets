<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToDepartment
{
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
