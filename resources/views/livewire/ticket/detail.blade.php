<div>
    <x-support::modal title="{{ str(__('support::messages.ticket'))->headline() }} - #{{ $ticket?->reference }}"
        modal-name="TicketDetails" event-name="{{ $this->modal_event_name_detail }}" class="modal-lg">
        <table class="table table-striped table-inverse table-sm">
            <tbody class="thead-inverse">
                <tr>
                    <th class="text-right">{{ str(__('support::messages.id'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->id }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.department'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->department?->name }}</td>
                </tr>
                <tr>
                    <th class="text-right">{{ str(__('support::messages.subject'))->headline() }}:</th>
                    <td class="text-left">{{ $ticket->subject?->name }}</td>
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
                    <td class="text-left {{ $ticket->subject?->priority->class() }}">{{
                        $ticket->subject?->priority->name
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
                @if ($ticket?->image)
                <tr>
                    <th class="text-right">{{ str(__('support::messages.image'))->headline() }}:</th>
                    <td class="text-left">
                        <a href="{{ $ticket->image_path }}" target="_image">
                            <img src="{{ $ticket->image_path }}" alt="{{ $ticket->id }}" class="img img-thumbnail"
                                style="max-width: 100px;
                        max-height: 100px;">
                        </a>
                    </td>
                </tr>

                @endif
            </tbody>
        </table>

        <x-slot name="footer" wire:key="ticket-footer-{{ $ticket?->id }}" class="p-0">
            <x-support::loading :remove-while-loading="true">
                {{-- Is current user the ticket's owner. Owner should not work tickets themself --}}
                @if($ticket->created_by === auth()->user()?->id)
                @include('support::livewire.ticket.roles._owner')
                {{-- // check if user is super admin --}}
                @elseif(auth()->user()?->isSupportSuperAdmin())
                @include('support::livewire.ticket.roles._super_admin')
                {{-- // Che if user is department admin --}}
                @elseif(auth()->user()->isDepartmentAdmin($ticket->department ?: new
                \Dainsys\Support\Models\Department()))
                @include('support::livewire.ticket.roles._admin')
                {{-- // check if user is department agent --}}
                @elseif(auth()->user()->isDepartmentAgent($ticket->department ?: new
                \Dainsys\Support\Models\Department()))
                @include('support::livewire.ticket.roles._agent')
                @endif
            </x-support::loading>

            <section id="replies" style="background-color: rgb(178, 229, 255);" class="p-1 w-100"
                wire:key="replies-section-{{ $ticket->id }}" x-data="{open: false}">
                @if ($ticket->isOpen())
                @can('create', [new Dainsys\Support\Models\Reply(), $ticket])
                <livewire:support::reply.form :ticket='$ticket' wire:key="replies-form-{{ $ticket->id }}"
                    modifier="lazy" wire:key="reply-ticket-{{ $ticket?->id }}" />
                @endcan
                @endif

                @if ($ticket?->replies->count() > 0)
                <div class="d-flex justify-content-end">
                    <a href="#" class="text-bold text-info" x-show="open == false" @click.prevent="open = true">Show
                        Replies</a>

                    <a href="#" class="text-bold text-purple" x-show="open" @click.prevent="open = false">Hide
                        Replies</a>
                </div>
                <div x-show="open">
                    @include('support::livewire.ticket._replies')
                </div>
                @endif
            </section>
        </x-slot>
    </x-support::modal>
</div>