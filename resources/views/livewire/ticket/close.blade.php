<div class="w-100 bg-gradiend" style="background-color: #ffebee;">
    <form class='needs-validation' autocomplete="false" wire:submit.prevent='closeTicket'>
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