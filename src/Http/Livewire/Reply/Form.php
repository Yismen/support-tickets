<?php

namespace Dainsys\Support\Http\Livewire\Reply;

use Livewire\Component;
use Dainsys\Support\Models\Reply;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;

    public bool $editing = false;
    public $ticket;
    public Reply $reply;

    public $modifier = 'lazy';
    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'showReplyForm' => 'create',
        'showReplyEdit' => 'edit',
    ];

    public function mount()
    {
        $this->ticket = new Ticket();
        $this->reply = new Reply();
    }

    public function render()
    {
        // $this->authorize('create', $this->reply);

        return view('support::livewire.reply.form')
        ->layout('support::layouts.app');
    }

    public function create(Ticket $ticket)
    {
        $this->authorize('create', [new Reply(), $ticket]);

        $this->editing = false;
        $this->ticket = $ticket;
        $this->resetValidation();
        $this->reply = new Reply();
    }

    public function store()
    {
        $this->authorize('create', [new Reply(), $this->ticket]);

        $this->validate();

        $this->ticket->replies()->create([
            'user_id' => auth()->user()->id,
            'content' => $this->reply->content,
        ]);

        supportFlash('Reply created!');

        $this->reply = new Reply();

        $this->emitUp('replyUpdated');
    }

    public function edit(Reply $reply)
    {
        $this->editing = true;
        $this->reply = $reply;
        $this->resetValidation();

        $this->dispatchBrowserEvent('updateReply');
    }

    public function update()
    {
        $this->validate();

        $this->reply->update([
            'content' => $this->reply['content']
        ]);

        supportFlash('Reply updated!');

        $this->reply = new Reply();

        $this->editing = false;

        $this->emit('replyUpdated');
    }

    public function cancel()
    {
        $this->reply = new Reply();

        $this->editing = false;
    }

    protected function getRules()
    {
        return [
            'reply.content' => [
                'required',
                'min:5'
            ]
        ];
    }
}
