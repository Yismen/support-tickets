<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), str(__('support::messages.department_role'))->headline(),
    str(__('support::messages.for'))->headline(), $user->name])
    : join(" ", [__('Create'),
    __('New'), __('DepartmentRole') ])
    @endphp

    <x-support::modal modal-name="DepartmentRoleForm" title="{{ $title }}"
        event-name="{{ $this->modal_event_name_form }}" :backdrop="false">

        <x-support::form :editing="$editing">
            <div class="p-3">
                <x-support::inputs.select field="department" :options='$departments'>
                    {{ str(__('support::messages.department'))->headline() }}:
                </x-support::inputs.select>

                <x-support::inputs.select field="role" :options='$roles'>
                    {{ str(__('support::messages.role'))->headline() }}:
                </x-support::inputs.select>
                {{-- <x-support::inputs.with-labels field="user.name">{{ str(
                    __('support::messages.name'))->headline()
                    }}:
                </x-support::inputs.with-labels>


                <x-support::inputs.text-area field="user.description" :required="false">
                    {{ str(__('support::messages.description'))->headline() }}:
                </x-support::inputs.text-area> --}}
            </div>
        </x-support::form>
    </x-support::modal>
</div>