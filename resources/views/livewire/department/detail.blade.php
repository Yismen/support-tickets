<div>
    <x-support::modal title="{{ str(__('support::messages.department'))->headline() }} - {{ $department->name ?? '' }}"
        modal-name="DepartmentDetails" event-name="{{ $this->modal_event_name_detail }}">

        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ str(__('support::messages.name'))->headline() }}:</th>
                    <td class="text-left">{{ $department->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.ticket_prefix'))->headline() }}:</th>
                    <td class="text-left">{{ $department->ticket_prefix ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.description'))->headline() }}:</th>
                    <td class="text-left">{{ $department->description ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateDepartment", {{ $department->id ?? '' }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>
        </x-slot>
    </x-support::modal>
</div>