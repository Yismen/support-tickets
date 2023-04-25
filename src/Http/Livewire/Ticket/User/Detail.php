<?php

namespace Dainsys\Support\Http\Livewire\Ticket\User;

use Livewire\Component;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Detail extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'showTicket',
    ];

    public bool $editing = false;
    public string $modal_event_name_detail = 'showTicketDetailModal';

    public Ticket $ticket;

    public function mount()
    {
        $this->ticket = new Ticket();
    }

    public function render()
    {
        // $this->authorize('view', $this->ticket);

        return view('support::livewire.ticket.user.detail')
        ->layout('support::layouts.app');
    }

    public function showTicket(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $this->editing = false;
        $this->ticket = $ticket;
        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_detail);
    }
}
