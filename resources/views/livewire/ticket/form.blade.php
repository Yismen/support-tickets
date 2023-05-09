<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Ticket'), "#".$ticket->id, $ticket->name]) : join(" ",
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
                        <x-support::inputs.select field='ticket.reason_id' :options='$reasons'>
                            {{ str(__('support::messages.reason'))->headline() }}:
                        </x-support::inputs.select>
                    </div>
                </div>

                <x-support::inputs.text-area field="ticket.description" rows="10" :editor=true>
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>

                <x-support::inputs.image :image="$image" field="image" current-image="{{ $ticket->image }}">
                    Ticket Image
                </x-support::inputs.image>

                @if ($image || $ticket->image)
                <div class="d-flex justify-content-end">
                    <a class="btn btn-danger btn-xs" wire:click.prevent='removeImage'>Remove Image</a>
                </div>
                @endif
            </div>
        </x-support::form>
        <div class="border-top d-flex justify-content-end p-2">
            @can('delete', $ticket)
            <div>
                <button class="btn btn-sm btn-danger text-uppercase" wire:click.prevent='delete'>
                    {{ str(__('support::messages.delete'))->headline() }}
                </button>
            </div>
            @endcan
        </div>
    </x-support::modal>
</div>