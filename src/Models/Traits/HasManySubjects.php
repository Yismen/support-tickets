<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\Subject;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManySubjects
{
    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
