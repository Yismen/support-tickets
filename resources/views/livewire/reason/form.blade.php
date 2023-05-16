<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Reason'), $reason->name]) : join(" ", [__('Create'),
    __('New'), __('Reason') ])
    @endphp

    <x-support::modal modal-name="ReasonForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">
                <x-support::inputs.with-labels field="reason.name">{{ str( __('support::messages.name'))->headline()
                    }}:
                </x-support::inputs.with-labels>

                <x-support::inputs.select field='reason.department_id' :options='$departments'>
                    {{ str(__('support::messages.department'))->headline() }}:
                </x-support::inputs.select>

                <x-support::inputs.radio-group field='reason.priority' :options='$priorities' :placeholder=false
                    class="form-check-inline">
                    {{ str(__('support::messages.priority'))->headline() }}:
                </x-support::inputs.radio-group>

                <x-support::inputs.text-area field="reason.description" :required="false">
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>
            </div>
        </x-support::form>
    </x-support::modal>
</div>