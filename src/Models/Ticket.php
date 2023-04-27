<?php

namespace Dainsys\Support\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use OwenIt\Auditing\Contracts\Auditable;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Enums\TicketProgressesEnum;
use Dainsys\Support\Database\Factories\TicketFactory;

class Ticket extends AbstractModel implements Auditable
{
    use \Dainsys\Support\Models\Traits\BelongsToDepartment;
    use \Dainsys\Support\Models\Traits\BelongsToReason;
    use \Dainsys\Support\Models\Traits\BelongsToAgent;
    use \Dainsys\Support\Models\Traits\BelongsToUser;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Dainsys\Support\Traits\EnsureNotWeekend;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['created_by', 'department_id', 'reason_id', 'description', 'progress', 'assigned_to', 'assigned_at', 'expected_at',  'priority', 'completed_at'];

    protected $casts = [
        'priority' => TicketPrioritiesEnum::class,
        'assigned_at' => 'datetime',
        'expected_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress' => TicketProgressesEnum::class,
    ];

    protected static function newFactory(): TicketFactory
    {
        return TicketFactory::new();
    }

    protected static function booted()
    {
        static::created(function ($model) {
            $model->updateQuietly([
                'progress' => TicketProgressesEnum::Pending,
                'assigned_to' => null,
                'assigned_at' => null
            ]);
        });
        static::saved(function ($model) {
            $model->updateQuietly([
                'expected_at' => $model->getExpectedDate()
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
            'progress' => TicketProgressesEnum::InProgress,
        ]);
    }

    public function complete()
    {
        $this->updateQuietly([
            'progress' => TicketProgressesEnum::Completed,
            'completed_at' => now(),
        ]);
    }

    protected function getExpectedDate(): Carbon
    {
        $date = $this->created_at ?? now();

        switch ($this->priority) {
            case TicketPrioritiesEnum::Normal:
                return $this->ensureNotWeekend($date->copy()->addDays(2));
                break;
            case TicketPrioritiesEnum::Medium:
                return $this->ensureNotWeekend($date->copy()->addDay());
                break;
            case TicketPrioritiesEnum::High:
                return $this->ensureNotWeekend($date->copy()->addMinutes(4 * 60));
                break;
            case TicketPrioritiesEnum::Emergency:
                return $this->ensureNotWeekend($date->copy()->addMinutes(30));
                break;

            default:
                return $this->ensureNotWeekend($date->copy()->addDays(2));
                break;
        }
    }
}
