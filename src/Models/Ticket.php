<?php

namespace Dainsys\Support\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Dainsys\Support\Database\Factories\TicketFactory;

class Ticket extends AbstractModel implements Auditable
{
    use \Dainsys\Support\Models\Traits\BelongsToDepartment;
    use \Dainsys\Support\Models\Traits\BelongsToUser;
    use \Dainsys\Support\Models\Traits\BelongsToReason;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['created_by', 'department_id', 'reason_id', 'description', 'assigned_to', 'assigned_at', 'expected_at', 'status', 'priority', 'completed_at'];

    protected static function newFactory(): TicketFactory
    {
        return TicketFactory::new();
    }

    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }
}
