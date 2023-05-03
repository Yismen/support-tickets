<?php

namespace Dainsys\Support\Models\Traits;

trait HasShortDescription
{
    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }
}
