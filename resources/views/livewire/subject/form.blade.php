<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Subject'), $subject->name]) : join(" ", [__('Create'),
    __('New'), __('Subject') ])
    @endphp

    <x-support::modal modal-name="SubjectForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">
                <x-support::inputs.with-labels field="subject.name">{{ str( __('support::messages.name'))->headline()
                    }}:
                </x-support::inputs.with-labels>

                <x-support::inputs.select field='subject.department_id' :options='$departments'>
                    {{ str(__('support::messages.department'))->headline() }}:
                </x-support::inputs.select>

                <x-support::inputs.radio-group field='subject.priority' :options='$priorities' :placeholder=false
                    class="form-check-inline">
                    {{ str(__('support::messages.priority'))->headline() }}:
                </x-support::inputs.radio-group>

                <x-support::inputs.text-area field="subject.description" :required="false">
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>
            </div>
        </x-support::form>
    </x-support::modal>
</div>