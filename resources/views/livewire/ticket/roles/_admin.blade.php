<div class="d-flex flex-column flex-fill">
    <h6 class="text-bold text-uppercase text-lightblue border-top">Department Admin Actions</h6>
    @if ($ticket->isOpen())
    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket->id }}" />


    @include('support::livewire.ticket._assign')


    @else

    @include('support::livewire.ticket._reopen')

    <span class="{{ $ticket->status->class() }} mt-2">
        {{ str($ticket->status->name)->headline() }}
    </span>
    @endif
</div>