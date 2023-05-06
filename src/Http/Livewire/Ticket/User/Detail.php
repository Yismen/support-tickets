<?php

namespace Dainsys\Support\Http\Livewire\Ticket\User;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Reply;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Detail extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $assign_to;

    protected $listeners = [
        'showTicket',
        'replyUpdated',
        'wantsGragTicket',
        'ticketUpdated' => '$refresh',
    ];
    public string $modal_event_name_detail = 'showTicketDetailModal';

    public Ticket $ticket;

    public function mount()
    {
        $this->authorize('viewAny', new Ticket());
        $this->ticket = new Ticket();
    }

    public function render()
    {
        $this->assign_to = $this->ticket->assigned_to;

        return view('support::livewire.ticket.user.detail', [
            'replies' => $this->ticket->replies()->latest()->with('user')->paginate(5, '*', 'repliesPage'),
            'team' => $this->ticket?->department?->team->load('user')->pluck('user.name', 'user_id')->toArray() ?: []
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

    public function grabTicket()
    {
        $this->authorize('grab-ticket', $this->ticket);

        $this->ticket->assignTo(auth()->user());

        $this->emit('ticketUpdated');

        supportFlash('Ticket is now assigned to you!', 'success');
    }

    public function updatedAssignTo()
    {
        $this->validateOnly('assign_to', [
            'assign_to' => [
                'required',
                Rule::exists(DepartmentRole::class, 'user_id'),
            ]
        ]);

        $agent = DepartmentRole::where('user_id', $this->assign_to)->firstOrFail();

        if ($agent) {
            $agent->load('user');

            $this->ticket->assignTo($agent);

            $this->emit('ticketUpdated');

            supportFlash("Ticket assigned to {$agent->user->name}!", 'success');
        }
    }

    public function reOpen()
    {
        $this->ticket->reOpen();

        $this->emit('ticketUpdated');

        supportFlash('Ticket is now open!', 'warning');
    }
}
