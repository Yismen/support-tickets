<?php

namespace Dainsys\Support\Http\Livewire\Ticket\User;

use Livewire\Component;
use Livewire\WithPagination;
use Dainsys\Support\Models\Reply;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Detail extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'showTicket',
        'replyUpdated',
    ];
    public string $modal_event_name_detail = 'showTicketDetailModal';

    public Ticket $ticket;

    public function mount()
    {
        $this->ticket = new Ticket();
    }

    public function render()
    {
        return view('support::livewire.ticket.user.detail', [
            'replies' => $this->ticket->replies()->latest()->with('user')->paginate(5, '*', 'repliesPage'),
        ])
        ->layout('support::layouts.app');
    }

    public function showTicket(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $this->ticket = $ticket;
        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_detail);
        $this->emit('showReplyForm', ['ticket' => $this->ticket]);

        $this->resetPage('repliesPage');
    }

    public function editReply(Reply $reply)
    {
        $this->emit('showReplyEdit', $reply);
    }

    public function replyUpdated()
    {
        $this->resetPage('repliesPage');
    }
}
