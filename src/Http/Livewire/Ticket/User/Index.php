<?php

namespace Dainsys\Support\Http\Livewire\Ticket\User;

use Livewire\Component;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Services\TicketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'ticketUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->authorize('viewAny', new Ticket());

        return view('support::livewire.ticket.user.index', [
            // 'tickets' => TicketService::list()
        ])
        ->layout('support::layouts.app');
    }
}
