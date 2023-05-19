<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Models\Traits\HasManyTickets;
use Dainsys\Support\Database\Factories\SubjectFactory;
use Dainsys\Support\Models\Traits\BelongsToDepartment;
use Dainsys\Support\Models\Traits\HasShortDescription;

class Subject extends AbstractModel
{
    use BelongsToDepartment;
    use HasShortDescription;
    use HasManyTickets;

    protected $fillable = ['name', 'department_id', 'priority', 'description'];

    protected $casts = [
        'priority' => TicketPrioritiesEnum::class,
    ];

    protected static function newFactory(): SubjectFactory
    {
        return SubjectFactory::new();
    }

    public function getNameWithPriorityAttribute()
    {
        return"{$this->name} - {$this->priority->name}";
    }
}
