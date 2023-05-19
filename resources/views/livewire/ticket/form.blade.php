<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Ticket'), " - #", $ticket->reference]) : join(" ",
    [__('Create'),
    __('New'), __('Ticket') ])
    @endphp

    <x-support::modal modal-name="TicketForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false" class="modal-xl">

        <x-support::form :editing="$editing">
            <div class="p-3">

                <div class="row">
                    <div class="col-sm-6">
                        <x-support::inputs.select field='ticket.department_id' :options='$departments'>
                            {{ str(__('support::messages.department'))->headline() }}:
                        </x-support::inputs.select>
                    </div>

                    <div class="col-sm-6">
                        <x-support::inputs.select field='ticket.subject_id' :options='$subjects'>
                            {{ str(__('support::messages.subject'))->headline() }}:
                        </x-support::inputs.select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-7">
                        <x-support::inputs.text-area field="ticket.description" rows="10" :editor=true>
                            {{ str(__('support::messages.description'))->headline() }}:
                        </x-support::inputs.text-area>
                    </div>
                    <div class="col-sm-5">
                        <x-support::inputs.image :image="$image" field="image" current-image="{{ $ticket->image }}">
                            Ticket Image
                        </x-support::inputs.image>
                        @if ($ticket->image)
                        <div class="d-flex">
                            <a class="btn btn-danger btn-xs" wire:click.prevent='removeImage'>Remove Image</a>
                        </div>
                        @endif
                    </div>
                </div>


            </div>
        </x-support::form>
        @if ($editing)
        <div class="border-top d-flex justify-content-end p-2">
            @can('delete', $ticket)
            <div>
                <button class="btn btn-sm btn-danger text-uppercase" wire:click.prevent='delete'>
                    {{ str(__('support::messages.delete'))->headline() }}
                </button>
            </div>
            @endcan
        </div>
        @endif
    </x-support::modal>
</div>