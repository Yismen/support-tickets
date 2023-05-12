<div class="w-100 bg-gradiend" x-data="{ open: false }">

    <button class="btn btn-xs btn-danger align-self-end" x-show="! open" @click.prevent="open = true">
        {{ str(__('support::messages.close'))->headline() }}
    </button>

    <form class='needs-validation' autocomplete="false" wire:submit.prevent='closeTicket' x-show="open"
        style="background-color: #ffebee;">

        <button class="btn btn-xs btn-secondary align-self-end" @click.prevent="open = false">
            {{ str(__('support::messages.cancel'))->headline() }}
        </button>


        <div class="p-3">
            <div class="row align-items-center">
                <div class="col-sm-10">
                    <x-support::inputs.text-area field='comment' rows="2">
                        {{ str(__('support::messages.comment'))->headline() }}:
                    </x-support::inputs.text-area>
                </div>

                <div class="col-sm-2">
                    <button class="btn btn-danger">
                        {{ str(__('support::messages.close') ." ".
                        __('support::messages.ticket'))->headline() }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>