<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Database\Factories\ReasonFactory;
use Dainsys\Support\Models\Traits\BelongsToDepartment;
use Dainsys\Support\Models\Traits\HasShortDescription;

class Reason extends AbstractModel
{
    use BelongsToDepartment;
    use HasShortDescription;

    protected $fillable = ['name', 'department_id', 'priority', 'description'];

    protected $casts = [
        'priority' => TicketPrioritiesEnum::class,
    ];

    protected static function newFactory(): ReasonFactory
    {
        return ReasonFactory::new();
    }
}
