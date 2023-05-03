<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Events\ReplyCreatedEvent;
use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Models\Traits\BelongsToTicket;
use Dainsys\Support\Database\Factories\ReplyFactory;
use Dainsys\Support\Models\Traits\HasShortDescription;

class Reply extends AbstractModel
{
    use BelongsToTicket;
    use BelongsToUser;
    use HasShortDescription;

    protected $fillable = ['user_id', 'ticket_id', 'content'];

    protected $notifiables = [];

    protected $dispatchesEvents = [
        'created' => ReplyCreatedEvent::class
    ];

    protected static function newFactory(): ReplyFactory
    {
        return ReplyFactory::new();
    }

    public function getNotifiables(): array
    {
        if (! $this->ticket) {
            return $this->notifiables;
        }
        $ticket = $this->ticket->load([
            'owner',
            'agent',
            'department',
        ]);

        $this->notifiables[] = $ticket->owner()->where('id', '!=', $this->user_id)->first();

        $ticket->department->agents()->with(['user'])->where('user_id', '!=', $this->user_id)->get()->each(function ($agent) {
            $this->notifiables[] = $agent->user;
        });

        $ticket->department->admins()->with(['user'])->where('user_id', '!=', $this->user_id)->get()->each(function ($admin) {
            $this->notifiables[] = $admin->user;
        });

        $this->notifiables = array_filter($this->notifiables, function ($notifiable) {
            return $notifiable && $notifiable->email;
        });

        return $this->notifiables;
    }
}
