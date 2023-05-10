<div class="d-flex flex-column flex-fill">
    <h6 class="text-bold text-uppercase text-lightblue border-top">Department Admin Actions</h6>
    @if ($ticket->isOpen())
    <div class="d-flex">
        <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket->id }}'" modifier="lazy"
            wire:key="close-ticket-{{ $ticket->id }}" />
    </div>

    <div class="d-flex justify-content-between mt-2 ">
        <div class="bg-teal mb-3 p-2">
            <x-support::inputs.select field="assign_to" :options='$team'>
                Assign Ticket To:
            </x-support::inputs.select>
        </div>
    </div>
    @else
    <div class="align-self-end">
        <button class="btn btn-sm btn-primary" wire:click.prevent='reOpen'>
            Reopen Ticket
        </button>
    </div>

    <span class="{{ $ticket->status->class() }} mt-2">
        {{ str($ticket->status->name)->headline() }}
    </span>
    @endif
</div>