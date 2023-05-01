<?php

namespace Dainsys\Support\Http\Livewire\Ticket\User;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Services\ReasonService;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;

    protected $listeners = [
        'createTicket',
        'updateTicket',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showTicketFormModal';

    public Ticket $ticket;

    public function mount()
    {
        $this->ticket = new Ticket();
    }

    public function render()
    {
        // dump($this->ticket);
        return view('support::livewire.ticket.user.form', [
            'departments' => DepartmentService::list(),
            'reasons' => ReasonService::listForDeaprtment($this->ticket->department_id),
            'priorities' => TicketPrioritiesEnum::asArray()
        ])
        ->layout('support::layouts.app');
    }

    public function createTicket($ticket = null)
    {
        $this->ticket = new Ticket();
        $this->authorize('create', $this->ticket);
        $this->editing = false;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function updateTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->authorize('update', $this->ticket);
        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function store()
    {
        $this->authorize('create', new Ticket());
        $this->validate();

        $this->editing = false;

        // $this->ticket->save();

        auth()->user()->tickets()->create($this->ticket->toArray());

        $this->dispatchBrowserEvent('closeAllModals');

        $this->emit('ticketUpdated');

        flasher('Ticket created!', 'success');
    }

    public function update()
    {
        $this->authorize('update', $this->ticket);
        $this->validate();

        $this->ticket->save();

        $this->dispatchBrowserEvent('closeAllModals');

        $this->editing = false;

        $this->emit('ticketUpdated');

        flasher('Ticket updated!', 'success');
    }

    protected function getRules()
    {
        // dd(
        //     $this,
        //     array_column(TicketPrioritiesEnum::cases(), 'value')
        // );
        return [
            'ticket.department_id' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'ticket.reason_id' => [
                'required',
                Rule::exists(Reason::class, 'id')
            ],
            'ticket.description' => [
                'required',
                'min:3'
            ]
        ];
    }
}
