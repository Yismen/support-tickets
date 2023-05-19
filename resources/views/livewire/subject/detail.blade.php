<div>
    <x-support::modal title="{{ str(__('support::messages.subject'))->headline() }} - {{ $subject->name ?? '' }}"
        modal-name="SubjectDetails" event-name="{{ $this->modal_event_name_detail }}">

        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ str(__('support::messages.name'))->headline() }}:</th>
                    <td class="text-left">{{ $subject->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.department'))->headline() }}:</th>
                    <td class="text-left">{{ $subject->department->name ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.priority'))->headline() }}:</th>
                    <td class="text-left">{{ $subject->priority ?? '' }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.description'))->headline() }}:</th>
                    <td class="text-left">{{ $subject->description ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateSubject", {{ $subject->id ?? '' }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>
        </x-slot>
    </x-support::modal>
</div>