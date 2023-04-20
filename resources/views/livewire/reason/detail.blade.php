<div>
    <x-support::modal title="{{ str(__('support::messages.reason'))->headline() }} - {{ $reason->name ?? '' }}"
        modal-name="ReasonDetails" event-name="{{ $this->modal_event_name_detail }}">

        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ str(__('support::messages.name'))->headline() }}:</th>
                    <td class="text-left">{{ $reason->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.department'))->headline() }}:</th>
                    <td class="text-left">{{ $reason->department->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.description'))->headline() }}:</th>
                    <td class="text-left">{{ $reason->description ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateReason", {{ $reason->id ?? '' }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>
        </x-slot>
    </x-support::modal>
</div>