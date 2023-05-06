<div>
    <x-support::modal title="{{ str(__('support::messages.ticket'))->headline() }} - #{{ $ticket?->id }}"
        modal-name="TicketDetails" event-name="{{ $this->modal_event_name_detail }}" class="modal-lg">
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
                    <td class="text-left {{ $ticket->reason?->priority->class() }}">{{ $ticket->reason?->priority->name
                        }}</td>
                </tr>
                @if ($ticket->owner?->id !== auth()->user()?->id)
                <tr>
                    <th class="text-right">{{ str(__('support::messages.owner'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->owner?->name
                        }}</td>
                </tr>
                @endif
                <tr>
                    <th class="text-right">{{ str(__('support::messages.status'))->headline() }}:</th>
                    <td class="text-left {{ $ticket->status?->class() }}">{{ str($ticket->status?->name)->headline() }}
                    </td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.dued_at'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket?->expected_at?->diffForHumans() }} </td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.description'))->headline() }}:</th>
                    <td class="text-left">{!! $ticket->description !!}</td>
                </tr>
            </tbody>
        </table>

        <section id="replies" style="background-color: #f9f9f9;">
            {{-- <div wire:ignore.self> --}}
                @can('create', [new Dainsys\Support\Models\Reply(), $ticket])
                <livewire:support::reply.form ticket='{{ $ticket }}' :key="'replies-form'" modifier="lazy"
                    wire:key="reply-ticket-{{ $ticket?->id }}" />
                @endcan
                {{--
            </div> --}}

            @include('support::livewire.ticket.user.replies')
        </section>

        <x-slot name="footer">
            @can('own-ticket', $ticket)
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateTicket", {{ $ticket?->id }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>

            @else

            @if ($ticket->isAssigned())

            @if ($ticket->isAssignedTo(auth()->user()))

            @can('close-ticket', $ticket)
            <livewire:support::ticket.close :ticket='$ticket' :wire:key="'replies-form-{{ $ticket?->id }}'"
                modifier="lazy" wire:key="close-ticket-{{ $ticket?->id }}" />
            @endcan
            @else

            Assigned to other
            @endif

            @else
            <button class="btn btn-sm btn-warning" wire:click='$emit("grabTicket", {{ $ticket }})'>{{
                str(__('support::messages.grab'). ' '.
                __('support::messages.ticket'))->headline()
                }}</button>
            @endif
            @endcan
        </x-slot>
    </x-support::modal>
</div>