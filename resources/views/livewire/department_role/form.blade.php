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
            </div>
        </x-support::form>

        @if ($this->user?->departmentRole)
        @can('delete', $this->user?->departmentRole)
        <x-slot name="footer">
            <a href="#" class="btn btn-danger btn-sm text-uppercase" wire:click.prevent='deleteRole'>
                {{ __('support::messages.delete') }}
            </a>
        </x-slot>
        @endcan

        @endif
    </x-support::modal>
</div>