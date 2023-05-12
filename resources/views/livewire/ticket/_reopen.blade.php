<div class="align-self-end">
    <button class="btn btn-sm btn-primary" wire:click.prevent='reOpen'>
        {{ str(
        __('support::messages.reopen')
        . ' ' .
        __('support::messages.ticket')
        )->headline() }}
    </button>
</div>