<div class="d-flex justify-content-between flex-fill flex-column">
    <h6 class="text-bold text-uppercase text-fuchsia border-top">Department Agent Actions</h6>
    @if ($ticket->isAssigned())

    @if ($ticket->isAssignedToMe() && $ticket->isOpen())
    <div class="flex-fill">
        <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket->id }}'" modifier="lazy"
            wire:key="close-ticket-{{ $ticket->id }}" />
    </div>
    @else

    <span class="badge badge-info ">
        Ticket currently assiged to {{ auth()->user()->name }}
    </span>

    @endif

    @else

    <div>
        <button class="btn btn-sm btn-warning" wire:click='grabTicket()'>Grab Ticket</button>
    </div>

    @endif
</div>