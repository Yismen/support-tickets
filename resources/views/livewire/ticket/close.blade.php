<div class="w-100 bg-gradiend" x-data="{ open: false }">

    <button class="btn btn-xs btn-danger align-self-end" x-show="! open" @click.prevent="open = true">
        {{ str(__('support::messages.close'))->headline() }}
    </button>

    <x-support::loading target="closeTicket, closeTicketConfirmed">
        <form class="needs-validation" autocomplete="false" wire:submit.prevent='closeTicket' x-show="open"
            style="background-color: #ffebee;">

            <div class="d-flex flex-column">
                <div class="align-self-end mt-2 mr-2">
                    <button class="btn btn-xs btn-secondary " @click.prevent="open = false"
                        title="{{ str(__('support::messages.cancel'))->headline() }}">
                        X
                    </button>
                </div>
                <div class="row align-items-center p-3">
                    <div class="col-sm-10">
                        <x-support::inputs.text-area field='comment' rows="2" modifier=".defer">
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
    </x-support::loading>
</div>