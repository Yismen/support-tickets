@can('update', $ticket)
<div class="align-self-end">
    <button class="btn btn-warning btn-sm mb-2" wire:click='$emit("updateTicket", {{ $ticket?->id }})'>
        {{ str(__('support::messages.edit'))->upper() }}
    </button>
</div>
@endcan