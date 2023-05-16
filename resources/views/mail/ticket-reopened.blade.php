@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, was
reopened by {{ $user?->name }}!.

**Title: {{ $ticket->reason->name }}**

*Content:*
> {!! $ticket->description !!}

@component('mail::button', ['url' => url('/support/my_tickets')])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent