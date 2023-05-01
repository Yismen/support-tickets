<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Ticket'), $ticket->name]) : join(" ", [__('Create'),
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

                <x-support::inputs.text-area field="ticket.description" rows="10" :editor=false :editor=true>
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>
            </div>
        </x-support::form>
    </x-support::modal>
</div>