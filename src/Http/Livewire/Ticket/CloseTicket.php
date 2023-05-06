<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Livewire\Component;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CloseTicket extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;

    public Ticket $ticket;

    public string $comment;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function render()
    {
        return view('support::livewire.ticket.close', [
        ])
        ->layout('support::layouts.app');
    }

    public function closeTicket()
    {
        $this->validate();

        $this->authorize('close-ticket', $this->ticket);

        $this->ticket->close($this->comment);

        $this->emit('ticketUpdated');
        $this->emit('showTicket', $this->ticket);
    }

    protected function getRules()
    {
        return [
            'comment' => [
                'required',
                'min:5'
            ]
        ];
    }
}
