<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Department'), $department->name]) : join(" ", [__('Create'),
    __('New'), __('Department') ])
    @endphp

    <x-support::modal modal-name="DepartmentForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">
                <x-support::inputs.with-labels field="department.name">{{ __('support::messages.name') }}:
                </x-support::inputs.with-labels>
                <x-support::inputs.switch field="department.active">{{ __('support::messages.active') }}:
                </x-support::inputs.switch>

                <x-support::inputs.text-area field="department.description" :required="false">
                    {{__('support::messages.description') }}:
                </x-support::inputs.text-area>

                <h5 class="border-top pt-1">{{ __('support::messages.departments') }}</h5>
                @foreach ($departments_list as $department_id => $department_name)
                <x-support::inputs.switch field="departments" value="{{ $department_id }}"
                    wire:key='department{{ $department_id }}'>{{ $department_name }}
                </x-support::inputs.switch>
                @endforeach
            </div>
        </x-support::form>
    </x-support::modal>
</div>