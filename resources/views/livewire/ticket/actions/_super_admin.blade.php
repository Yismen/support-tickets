<div class="d-flex justify-content-between flex-fill flex-column">
    <h6 class="text-bold text-uppercase text-success border-top">Super Admin Actions</h6>

    <div class="align-self-end">
        <button class="btn btn-warning btn-sm mb-2" wire:click='$emit("updateTicket", {{ $ticket?->id }})'>
            {{ str(__('support::messages.edit'))->upper() }}
        </button>
    </div>

    @if ($ticket->isOpen())
    <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'" modifier="lazy"
        wire:key="close-ticket-{{ $ticket?->id }}" />
    <div class="bg-teal mt-2 p-2 d-block">
        <x-support::inputs.select field="assign_to" :options='$team'>
            Assign Ticket To:
        </x-support::inputs.select>
    </div>
    @else
    <div class="align-self-end">
        <button class="btn btn-sm btn-primary" wire:click.prevent='reOpen'>
            Reopen Ticket
        </button>
    </div>
    @endif
</div>