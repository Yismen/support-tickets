<?php

namespace Dainsys\Support\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Events\TicketCreatedEvent;
use Dainsys\Support\Events\TicketDeletedEvent;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Events\TicketAssignedEvent;
use Dainsys\Support\Events\TicketReopenedEvent;
use Dainsys\Support\Events\TicketCompletedEvent;
use Dainsys\Support\Services\ImageCreatorService;
use Dainsys\Support\Database\Factories\TicketFactory;
use Dainsys\Support\Exceptions\DifferentDepartmentException;

class Ticket extends AbstractModel implements Auditable
{
    use \Dainsys\Support\Models\Traits\BelongsToDepartment;
    use \Dainsys\Support\Models\Traits\HasShortDescription;
    use \Dainsys\Support\Models\Scopes\Dates\PeriodScope;
    use \Dainsys\Support\Models\Traits\BelongsToSubject;
    use \Dainsys\Support\Models\Traits\HasManyReplies;
    use \Dainsys\Support\Models\Traits\BelongsToAgent;
    use \Dainsys\Support\Traits\EnsureDateNotWeekend;
    use \Dainsys\Support\Models\Traits\BelongsToOwner;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Dainsys\Support\Models\Traits\HasOneRating;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['created_by', 'department_id', 'subject_id', 'description', 'status', 'assigned_to', 'assigned_at', 'expected_at', 'completed_at', 'reference', 'image'];

    protected $casts = [
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
        parent::booted();

        static::created(function ($model) {
            $model->updateQuietly([
                // 'status' => TicketStatusesEnum::Pending,
                'assigned_to' => null,
                'assigned_at' => null,
                'reference' => $model->getReference(),
            ]);

            TicketCreatedEvent::dispatch($model);
        });

        static::saved(function ($model) {
            $model->updateQuietly([
                'expected_at' => $model->getExpectedDate(),
            ]);
            $model->updateQuietly([
                'status' => $model->getStatus(),
            ]);
        });
        static::deleting(function ($model) {
            if ($model->image) {
                $imageCreatorService = new ImageCreatorService();

                $imageCreatorService->delete($model->image);
            }
        });
        static::deleted(function ($model) {
            TicketDeletedEvent::dispatch($model);
        });
    }

    public function assignTo(DepartmentRole|User|int $agent)
    {
        if (is_integer($agent)) {
            $agent = DepartmentRole::findOrFail($agent);
        }

        if ($agent instanceof User) {
            $agent = DepartmentRole::where('user_id', $agent->id)->firstOrFail();
        }

        if ($agent->department_id !== $this->department_id) {
            throw new DifferentDepartmentException();
        }

        $this->update([
            'assigned_to' => $agent->user_id,
            'assigned_at' => now(),
            'status' => $this->getStatus(),
        ]);

        TicketAssignedEvent::dispatch($this);
    }

    public function reOpen()
    {
        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => null,
        ]);

        $this->rating()->delete();

        TicketReopenedEvent::dispatch($this);
    }

    public function complete(string $comment = '')
    {
        $this->update([
            'status' => $this->getStatus(),
            'completed_at' => now(),
        ]);

        $this->rating()->delete();

        TicketCompletedEvent::dispatch($this, $comment);
    }

    public function close(string $comment)
    {
        $this->replies()->createQuietly([
            'user_id' => auth()->user()->id,
            'content' => $comment
        ]);

        $this->complete($comment);
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    public function isAssignedToMe(): bool
    {
        return $this->assigned_to === auth()->user()->id;
    }

    public function isOpen(): bool
    {
        return is_null($this->completed_at);
    }

    public function isAssignedTo(DepartmentRole|User|int $agent): bool
    {
        if (is_integer($agent)) {
            $agent = DepartmentRole::findOrFail($agent);
        }

        if ($agent instanceof User) {
            $agent = DepartmentRole::where('user_id', $agent->id)->firstOrFail();
        }
        return $this->assigned_to === $agent->user_id;
    }

    public function updateImage($image, string $path = 'tickets', $name = null, int $resize = 400, int $quality = 90)
    {
        if ($image instanceof UploadedFile) {
            $imageCreatorService = new ImageCreatorService();

            $url = $imageCreatorService->make($image, $path, $name ?: $this->id, $resize, $quality);

            $this->updateQuietly([
                'image' => $url
            ]);
        }
    }

    public function getStatus(): TicketStatusesEnum
    {
        // Pendig
        if ($this->assigned_to == null && $this->completed_at == null) {
            return $this->expected_at > now()
                ? TicketStatusesEnum::Pending
                : TicketStatusesEnum::PendingExpired;
        }

        //    In Status
        if ($this->assigned_to && $this->completed_at == null) {
            return $this->expected_at > now()
                ? TicketStatusesEnum::InProgress
                : TicketStatusesEnum::InProgressExpired;
        }

        // Completed
        return $this->expected_at > $this->completed_at
            ? TicketStatusesEnum::Completed
            : TicketStatusesEnum::CompletedExpired;
    }

    public function scopeIncompleted(Builder $query): Builder
    {
        return $query->where('completed_at', null);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completed_at', '!=', null);
    }

    public function scopeCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '<', 'expected_at');
    }

    public function scopeNonCompliant(Builder $query): Builder
    {
        return $query->whereColumn('completed_at', '>', 'expected_at');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expected_at', '<', now());
    }

    public function getImagePathAttribute()
    {
        return Storage::url($this->image) . '?' . Str::random(5);
    }

    public function getPriorityAttribute()
    {
        return Cache::rememberForever('ticket_priority_' . $this->id, function () {
            return $this->subject->priority->value;
        });
    }

    public function getMailPriorityAttribute()
    {
        return $this->priority > 5 ? 5 : 5 - $this->priority;
    }

    public function admins()
    {
        return $this->department->roles()->where('role', DepartmentRolesEnum::Admin)->with('user')->get();
    }

    protected function getExpectedDate(): Carbon
    {
        $date = $this->created_at ?? now();

        switch ($this->subject->priority) {
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

    protected function getReference(): string
    {
        $latest_reference = self::query()->orderBy('reference', 'desc')->where('department_id', $this->department_id)->first()->reference;

        if ($latest_reference) {
            return ++$latest_reference;
        }

        return $this->department->ticket_prefix . '000001';
    }
}
