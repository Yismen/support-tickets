@if ($ticket->rating)
<div>
    Rated As: <span class="p-2 rounded {{ $ticket->rating->score->class() }}"> {{
        str($ticket->rating->score->name)->headline()
        }}
    </span>
</div>
@else
<livewire:support::ticket.rating key="ticket-rating-{{ $ticket->id }}" :ticket='$ticket' />
@endif