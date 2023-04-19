<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Department'), $department->name]) : join(" ", [__('Create'),
    __('New'), __('Department') ])
    @endphp

    <x-support::modal modal-name="DepartmentForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">
                <x-support::inputs.with-labels field="department.name">{{ str( __('support::messages.name'))->headline()
                    }}:
                </x-support::inputs.with-labels>

                <x-support::inputs.text-area field="department.description" :required="false">
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area>
            </div>
        </x-support::form>
    </x-support::modal>
</div>