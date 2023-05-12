<div class="d-flex flex-column flex-fill">
    <h6 class="text-bold text-uppercase text-cyan border-top">Ticket Owner Actions</h6>

    @include('support::livewire.ticket._edit-button')

    @if ($ticket->isOpen())
    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket?->id }}" />
    @else

    @include('support::livewire.ticket._reopen')

    @endif
</div>