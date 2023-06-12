<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Rules\MinText;
use Illuminate\Support\Facades\DB;
use Dainsys\Support\Models\Subject;
use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Support\Facades\Storage;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Services\SubjectService;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dainsys\Support\Http\Livewire\Traits\HasSweetAlertConfirmation;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;
    use WithFileUploads;
    use HasSweetAlertConfirmation;

    protected $listeners = [
        'createTicket',
        'updateTicket',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showTicketFormModal';

    public Ticket $ticket;

    public $image;
    public $assign_to;

    public function mount()
    {
        $this->ticket = new Ticket();
    }

    public function render()
    {
        return view('support::livewire.ticket.form', [
            'departments' => DepartmentService::list(),
            'subjects' => SubjectService::listForDeaprtment($this->ticket->department_id),
            // 'members' => \Dainsys\Support\Services\Department\DepartmentService::members($this->ticket->department_id)->pluck('name', 'id'),

            'members' => $this->ticket?->department?->team->load('user')->pluck('user.name', 'user_id')->toArray() ?: [],
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

            if ($this->assign_to) {
                $agent = DepartmentRole::where('user_id', $this->assign_to)->firstOrFail();

                $ticket->assignTo($agent);
            }
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
        $this->emit('showTicket', $this->ticket);
    }

    public function delete()
    {
        $this->authorize('delete', $this->ticket);

        $this->confirm('deleteTicketConfirmed', 'Deleting tickets is not reversable. Are you really sure you want to delete this ticket?');
    }

    public function removeImage()
    {
        $this->confirm('deleteImageConfirmed', 'Are you sure you want to delete this image from the ticket?');
    }

    protected function getRules()
    {
        return [
            'ticket.department_id' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'ticket.subject_id' => [
                'required',
                Rule::exists(Subject::class, 'id')
            ],
            'assign_to' => [
                'nullable',
                // Rule::exists(User::class, 'id')

                Rule::exists(DepartmentRole::class, 'user_id'),
            ],
            'ticket.description' => [
                'required',
                new MinText(3),
            ],
            'image' => 'nullable|image|max:1024'
        ];
    }

    protected function deleteImageConfirmed()
    {
        if ($this->ticket->image) {
            $this->ticket->updateQuietly(['image' => null]);

            if ($this->ticket->image) {
                Storage::delete([$this->ticket->image]);
            }
        }
        // $this->reset(['image']);
    }

    protected function deleteTicketConfirmed()
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
