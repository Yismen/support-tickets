<div class="d-flex justify-content-between flex-fill flex-column">
    <h6 class="text-bold text-uppercase text-success border-top">Super Admin Actions</h6>

    @include('support::livewire.ticket._edit-button')

    @if ($ticket->isOpen())
    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket?->id }}" />

    @include('support::livewire.ticket._assign')

    @else

    @include('support::livewire.ticket._rating')
    @include('support::livewire.ticket._reopen')

    @endif
</div>