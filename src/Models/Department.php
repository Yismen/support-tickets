<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Models\Traits\HasManyReasons;
use Dainsys\Support\Models\Traits\HasManyTickets;
use Dainsys\Support\Database\Factories\DepartmentFactory;

class Department extends AbstractModel
{
    use HasManyReasons;
    use HasManyTickets;
    protected $fillable = ['name', 'ticket_prefix', 'description'];

    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }

    public function setTicketPrefixAttribute($prefix)
    {
        $upper = str($prefix)->upper();
        $prefix = str($upper)->endsWith('-') ? $upper : "{$upper}-";

        $this->attributes['ticket_prefix'] = $prefix;
    }
}
