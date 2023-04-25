<div>
    <x-support::modal title="{{ str(__('support::messages.ticket'))->headline() }}" modal-name="TicketDetails"
        event-name="{{ $this->modal_event_name_detail }}" class="modal-lg">
        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ str(__('support::messages.department'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->department?->name }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.reason'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->reason?->name }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.status'))->headline() }}:</th>
                    <td class="text-left {{ $ticket->status?->class() }}">{{ $ticket->status?->name }}</td>
                </tr>
                @if ($ticket->completed_at ?? null)
                <tr>
                    <th class="text-right">{{ str(__('support::messages.completed_at'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->completed_at?->diffForHumans() }} </td>
                </tr>
                @endif
                <tr>
                    <th class="text-right">{{ str(__('support::messages.assigned_to'))->headline() }}:</th>
                    <td class="text-left">
                        @if ($ticket->assigned_to ?? null)
                        {{ $ticket->agent->name }}, {{ $ticket->assigned_at?->diffForHumans()
                        ??
                        '' }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.priority'))->headline() }}:</th>
                    <td class="text-left {{ $ticket->priority?->class() }}">{{ $ticket->priority?->name }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.dued_at'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket?->assigned_at?->diffForHumans() }} </td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.description'))->headline() }}:</th>
                    <td class="text-left">{!! $ticket->description !!}</td>
                </tr>
            </tbody>
        </table>

        @if ($ticket?->replies)
        <div class="px-3 mb-4 border-top pt-2">
            <h5 class="text-bold text-dark">{{ str(__('support::messages.replies'))->headline() }}</h5>
            <div class="text-sm">
                <div class="d-flex justify-content-between">
                    <span class="text-bold text-black text-uppercase ">User Name</span>
                    <span class="text-secondary text-sm">3 days ago</span>
                </div>
                <div class="bg-gradient bg-light ml-5 mt-2 p-2 rounded">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi deserunt, asperiores dolor nam nemo
                    corrupti consequatur, excepturi accusamus ducimus quidem dolorum sed porro debitis neque praesentium
                    ex
                    beatae, qui eligendi!
                </div>
            </div>

            <div class="text-sm">
                <div class="d-flex justify-content-between">
                    <span class="text-bold text-black text-uppercase ">User Name</span>
                    <span class="text-secondary text-sm">3 days ago</span>
                </div>
                <div class="bg-gradient bg-light ml-5 mt-2 p-2 rounded">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Modi deserunt, asperiores dolor nam nemo
                    corrupti consequatur, excepturi accusamus ducimus quidem dolorum sed porro debitis neque praesentium
                    ex
                    beatae, qui eligendi!
                </div>
            </div>
        </div>
        @endif

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateTicket", {{ $ticket->id }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>
        </x-slot>
    </x-support::modal>
</div>