<?php

namespace Dainsys\Support\Models;

use Illuminate\Foundation\Auth\User;
use OwenIt\Auditing\Contracts\Auditable;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Database\Factories\TicketFactory;

class Ticket extends AbstractModel implements Auditable
{
    use \Dainsys\Support\Models\Traits\BelongsToDepartment;
    use \Dainsys\Support\Models\Traits\BelongsToReason;
    use \Dainsys\Support\Models\Traits\BelongsToAgent;
    use \Dainsys\Support\Models\Traits\BelongsToUser;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['created_by', 'department_id', 'reason_id', 'description', 'status', 'assigned_to', 'assigned_at', 'expected_at',  'priority', 'completed_at'];

    protected $casts = [
        'priority' => TicketPrioritiesEnum::class,
        'assigned_at' => 'datetime',
        'expected_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => TicketStatusesEnum::class,
    ];

    protected static function newFactory(): TicketFactory
    {
        return TicketFactory::new();
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $model->updateQuietly([
                'status' => TicketStatusesEnum::Pending,
                'assigned_to' => null
            ]);
        });
        static::saved(function ($model) {
            $model->updateQuietly([
                'expected_at' => $model->priority->expectedAt()
            ]);
        });
    }

    public function getShortDescriptionAttribute()
    {
        return str($this->attributes['description'] ?? '')->limit(25);
    }

    public function assignTo(User|int $agent)
    {
        if (is_integer($agent)) {
            $agent = User::findOrFail($agent);
        }

        $this->updateQuietly([
            'assigned_to' => $agent->id,
            'assigned_at' => now(),
            'status' => TicketStatusesEnum::InProgress,
        ]);
    }

    public function complete()
    {
        $this->updateQuietly([
            'status' => TicketStatusesEnum::Completed,
            'completed_at' => now(),
        ]);
    }
}
