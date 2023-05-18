<div class="d-flex justify-content-between flex-fill flex-column">
    <h6 class="text-bold text-uppercase text-cyan border-top">Ticket Owner Actions</h6>

    @include('support::livewire.ticket._edit-button')

    @if ($ticket->isOpen())

    @if (auth()->user()->isSupportSuperAdmin())
    @include('support::livewire.ticket._assign')
    @endif

    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket?->id }}" />
    @else
    {{-- not open --}}
    @include('support::livewire.ticket._rating')
    @include('support::livewire.ticket._reopen')

    @endif
</div>