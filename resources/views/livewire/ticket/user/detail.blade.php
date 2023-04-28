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
            <div wire:ignore.self>
                <livewire:support::reply.form ticket='{{ $ticket }}' :key="'asdfasfd-dff'" modifier="lazy" />
            </div>

            @if ($ticket?->replies->count())
            <div class="px-3 mb-4 border-top pt-2">
                <h5 class="text-bold text-dark">
                    {{ str(__('support::messages.replies'))->headline() }}
                    <span class="badge badge-primary badge-btn">{{ $ticket->replies->count() }}</span>
                </h5>
                @foreach ($replies as $reply)
                <div class="text-sm border-bottom">
                    <div class="p-2
                        @can('update', $reply)
                            ml-5 bg-light
                        @else
                            mr-5 bg-cyan
                        @endcan
                    ">
                        <div class="d-flex justify-content-between">
                            <div class="text-bold text-uppercase">{{ $reply->user->name }}</div>
                            <div class="text-xs">{{ $reply->updated_at?->diffForHumans() }}</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            {{ $reply->content }}
                            @can('update', $reply)
                            <div class="">
                                <a wire:click.prevent='editReply({{ $reply->id }})'
                                    class="btn btn-link text-warning text-bold">{{
                                    str(__("support::messages.edit"))->headline() }}</a>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{ $replies->links() }}
            @endif
        </section>

        <x-slot name="footer">
            <button class="btn btn-warning btn-sm" wire:click='$emit("updateTicket", {{ $ticket->id }})'>
                {{ str(__('support::messages.edit'))->upper() }}
            </button>
        </x-slot>
    </x-support::modal>
</div>