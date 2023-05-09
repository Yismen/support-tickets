<div class="d-flex flex-column flex-fill">
    <h6 class="text-bold text-uppercase text-cyan border-top">Ticket Owner Actions</h6>
    <div class="align-self-end">
        <button class="btn btn-warning btn-sm mb-2" wire:click='$emit("updateTicket", {{ $ticket?->id }})'>
            {{ str(__('support::messages.edit'))->upper() }}
        </button>
    </div>
    @if ($ticket->isOpen())
    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket?->id }}" />
    @else


    <div class="align-self-end">
        <button class="btn btn-sm btn-primary" wire:click.prevent='reOpen'>
            Reopen Ticket
        </button>
    </div>
    @endif
</div>