<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Mail\TicketCreatedMail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Events\TicketCreatedEvent;

class SendTicketCreatedMail
{
    public function handle(TicketCreatedEvent $event)
    {
        $recipients = $this->recipients($event->ticket);

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketCreatedMail($event->ticket));
        }
    }

    protected function recipients(Ticket $ticket): Collection
    {
        $super_admins = SupportSuperAdmin::get()->map->user;
        $department_members = \Dainsys\Support\Models\DepartmentRole::query()->with('user')->where('department_id', $ticket->department_id)->get()->map->user;

        return (new Collection())
            ->merge($super_admins)
            ->merge($department_members)
            ->filter(function ($user) use ($ticket) {
                return $user->id !== $ticket->created_by;
            })
            ->filter(function ($user) {
                return $user?->email
                // || $user->id !== auth()->user()->id
                ;
            });
    }
}
