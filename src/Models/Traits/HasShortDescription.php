<?php

namespace Dainsys\Support\Models\Traits;

trait HasShortDescription
{
    public function getShortDescriptionAttribute()
    {
        return str(strip_tags($this->attributes['description']) ?? '')->limit(25);
    }
}
