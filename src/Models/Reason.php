<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Database\Factories\ReasonFactory;
use Dainsys\Support\Models\Traits\BelongsToDepartment;

class Reason extends AbstractModel
{
    use BelongsToDepartment;

    protected $fillable = ['name', 'department_id', 'description'];

    protected static function newFactory(): ReasonFactory
    {
        return ReasonFactory::new();
    }

    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }
}
