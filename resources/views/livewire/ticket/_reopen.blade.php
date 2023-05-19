@can('reopen-ticket', $ticket)
<div class="align-self-end">
    <button class="btn btn-xs btn-primary" wire:click.prevent='reOpen'>
        {{ str(
        __('support::messages.reopen')
        . ' ' .
        __('support::messages.ticket')
        )->headline() }}
    </button>
</div>
@endcan