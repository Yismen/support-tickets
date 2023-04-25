<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Ticket'), $ticket->name]) : join(" ", [__('Create'),
    __('New'), __('Ticket') ])
    @endphp

    <x-support::modal modal-name="TicketForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">

                <x-support::inputs.select field='ticket.department_id' :options='$departments'>
                    {{ str(__('support::messages.department'))->headline() }}:
                </x-support::inputs.select>

                @if (empty($ticket->department_id) === false)
                <x-support::inputs.select field='ticket.reason_id' :options='$reasons'>
                    {{ str(__('support::messages.reason'))->headline() }}:
                </x-support::inputs.select>
                @endif

                <x-support::inputs.text-area field="ticket.description">
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>

                <x-support::inputs.radios field='ticket.priority' :options='$priorities' :placeholder=false>
                    {{ str(__('support::messages.priority'))->headline() }}:
                </x-support::inputs.radios>


            </div>
        </x-support::form>
    </x-support::modal>
</div>