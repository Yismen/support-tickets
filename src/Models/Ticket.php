<?php

namespace Dainsys\Support\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
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
    use \Dainsys\Support\Traits\EnsureDateNotWeekend;
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
                'assigned_to' => null,
                'assigned_at' => null
            ]);
        });
        static::saved(function ($model) {
            $model->updateQuietly([
                'expected_at' => $model->getExpectedDate(),
            ]);
            $model->updateQuietly([
                'status' => $model->getStatus(),
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
        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => now(),
        ]);
    }

    public function scopeIncompleted(Builder $query): Builder
    {
        return $query->where('completed_at', null);
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

    public function getStatus(): TicketStatusesEnum
    {
        // Pendig
        if ($this->assigned_at == null && $this->completed_at == null) {
            return $this->expected_at > now()
                ? TicketStatusesEnum::Pending
                : TicketStatusesEnum::PendingExpired;
        }

        //    In Status
        if ($this->assigned_at && $this->completed_at == null) {
            return $this->expected_at > now()
            ? TicketStatusesEnum::InProgress
            : TicketStatusesEnum::InProgressExpired;
        }

        // Completed
        return $this->expected_at > $this->completed_at
            ? TicketStatusesEnum::Completed
            : TicketStatusesEnum::CompletedExpired;
    }
}
