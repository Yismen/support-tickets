<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Rules\MinText;
use Illuminate\Support\Facades\DB;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Storage;
use Dainsys\Support\Services\ReasonService;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;
    use WithFileUploads;

    protected $listeners = [
        'createTicket',
        'updateTicket',
        'sweetalertConfirmed',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showTicketFormModal';

    public Ticket $ticket;

    public $image;

    public function mount()
    {
        $this->ticket = new Ticket();
    }

    public function render()
    {
        return view('support::livewire.ticket.form', [
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

        $this->reset([
            'image',
            'editing'
        ]);

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function updateTicket(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->authorize('update', $this->ticket);

        $this->reset([
            'image',
        ]);

        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function store()
    {
        $this->authorize('create', new Ticket());
        $this->validate();

        DB::transaction(function () {
            $ticket = auth()->user()->tickets()->create($this->ticket->toArray());

            $ticket->updateImage($this->image);
        });

        $this->reset([
            'image',
            'editing'
        ]);

        $this->dispatchBrowserEvent('closeAllModals');

        $this->emit('ticketUpdated');

        supportFlash('Ticket created!', 'success');
    }

    public function update()
    {
        $this->authorize('update', $this->ticket);
        $this->validate();

        DB::transaction(function () {
            $this->ticket->save();

            $this->ticket->updateImage($this->image);
        });

        $this->dispatchBrowserEvent('closeAllModals');
        $this->reset([
            'image',
            'editing'
        ]);

        $this->emit('ticketUpdated');

        supportFlash('Ticket updated!', 'success');
    }

    public function delete()
    {
        $this->authorize('delete', $this->ticket);

        supportConfirm('delete_ticket', 'Are you sure you want to delete this ticket?');
    }

    public function removeImage()
    {
        supportConfirm('remove_image', 'Are you sure you want to delete this image from the ticket?');
    }

    public function sweetalertConfirmed(array $payload)
    {
        $event_name = $payload['envelope']['notification']['options']['confirmation_name'] ?? null;

        if ($event_name === 'delete_ticket') {
            $this->confirmDeleteTicket();
        }

        if ($event_name === 'remove_image') {
            $this->confirmDeleteImage();
        }
    }

    protected function getRules()
    {
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
                new MinText(3),
            ],
            'image' => 'nullable|image|max:1024'
        ];
    }

    protected function confirmDeleteImage()
    {
        if ($this->ticket->image) {
            $this->ticket->updateQuietly(['image' => null]);

            if ($this->ticket->image) {
                Storage::delete([$this->ticket->image]);
            }
        }
        $this->reset(['image']);
    }

    protected function confirmDeleteTicket()
    {
        $this->authorize('delete', $this->ticket);

        $this->ticket->delete();

        $this->reset([
            'image',
            'editing'
        ]);

        $this->dispatchBrowserEvent('closeAllModals');
        $this->emit('ticketUpdated');

        supportFlash("Ticket #{$this->ticket->id} has been deleted!", 'error');
    }
}