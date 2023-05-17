<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Rating;
use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Enums\TicketRatingsEnum;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dainsys\Support\Http\Livewire\Traits\HasSweetAlertConfirmation;

class RateTicket extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;
    use HasSweetAlertConfirmation;

    public Ticket $ticket;
    public Rating $rating;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->rating = new Rating(['user_id' => auth()->user()->id]);
    }

    public function render()
    {
        return view('support::livewire.ticket.rating', [
            'ratings' => TicketRatingsEnum::cases()
        ])
        ->layout('support::layouts.app');
    }

    public function rateTicket()
    {
        $this->authorize('create', $this->rating);
        $this->validate();

        $this->ticket->rating()->create($this->rating->toArray());

        $this->emit('ticketUpdated');
        // $this->emit('showTicket', $this->ticket);
    }

    protected function getRules()
    {
        return [
            'rating.user_id' => [
                'required',
                Rule::exists(User::class, 'id'),
            ],
            'rating.score' => [
                'required',
                Rule::in(array_column(TicketRatingsEnum::cases(), 'value')),
            ],
            'rating.comment' => [
                'nullable',
                'min:5'
            ]
        ];
    }
}
