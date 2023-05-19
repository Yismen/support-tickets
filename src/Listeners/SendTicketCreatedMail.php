<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Mail\TicketCreatedMail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Events\TicketCreatedEvent;

class SendTicketCreatedMail
{
    protected Ticket $ticket;

    public function handle(TicketCreatedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCreatedMail($this->ticket));
        }
    }

    protected function recipients(): Collection
    {
        $super_admins = SupportSuperAdmin::get()->map->user;
        $department_members = DepartmentRole::query()
            ->with('user')
            ->where('department_id', $this->ticket->department_id)
            ->get()->map->user;

        $recipients = (new Collection())
            ->merge($super_admins)
            ->merge($department_members)
            ->push($this->ticket->owner)
            // ->filter(function ($user) {
            //     return $user->id !== $this->ticket->created_by;
            // })
            ->filter(function ($user) {
                return $user?->email;
            });

        return config('support.email.include_current_user', false)
            ? $recipients
            : $recipients->filter(function ($user) {
                return $user->id !== auth()->user()?->id;
            });
    }
}
