<?php

namespace Dainsys\Support\Http\Livewire\Ticket\Department;

use Livewire\Component;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    public Department $department;
    public $selected;

    public function mount(Department $department)
    {
        $this->department = $department;
    }

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'grabTicket'
    ];

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.ticket.department.index', [
            'department' => $this->department,
            'total_tickets' => $this->department->tickets()->count(),
            'tickets_open' => $this->department->tickets_incompleted,
            'tickets_closed' => $this->department->tickets_completed,
            'compliance_rate' => $this->department->compliance_rate,
            'completion_rate' => $this->department->completion_rate,
        ])
        ->layout('support::layouts.app');
    }

    public function grabTicket(Ticket $ticket)
    {
        $this->authorize('grab-ticket', $ticket);

        $ticket->assignTo(auth()->user());

        $this->emit('ticketUpdated');
    }
}