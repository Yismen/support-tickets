<?php

namespace Dainsys\Support\Listeners;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Mail\TicketAssignedMail;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Events\TicketAssignedEvent;

class SendTicketAssignedMail
{
    protected Ticket $ticket;

    public function handle(TicketAssignedEvent $event)
    {
        $this->ticket = $event->ticket;

        $recipients = $this->recipients();

        if ($recipients->count()) {
            Mail::to($recipients)
                ->send(new TicketAssignedMail($this->ticket));
        }
    }

    protected function recipients(): Collection
    {
        $super_admins = SupportSuperAdmin::get()->map->user;
        $department_admins = DepartmentRole::query()
            ->with('user')
            ->where('role', DepartmentRolesEnum::Admin)
            ->where('department_id', $this->ticket->department_id)->get()->map->user;

        return (new Collection())
            ->merge($super_admins)
            ->merge($department_admins)
            ->push($this->ticket->agent)
            ->push($this->ticket->owner)
            ->filter(function ($user) {
                return $user?->email
                // || $user->id !== auth()->user()->id
                ;
            });
    }
}
