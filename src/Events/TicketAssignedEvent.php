<?php

namespace Dainsys\Support\Events;

use Dainsys\Support\Models\Ticket;
use Illuminate\Queue\SerializesModels;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TicketAssignedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Ticket $ticket;
    public DepartmentRole $agent;

    public function __construct(Ticket $ticket, DepartmentRole $agent)
    {
        // notifiy ticket owner
        // notify ticket agent
        // notify department admin
        // super admins
        $this->ticket = $ticket;
        $this->agent = $agent;
    }
}
