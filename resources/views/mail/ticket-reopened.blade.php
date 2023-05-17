@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, was
reopened by {{ $user?->name }}!.

**Title: {{ $ticket->reason->name }}**

*Content:*
> {!! $ticket->description !!}

@component('mail::button', ['url' => route('support.my_tickets', ['ticket_details' => $ticket->id])])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent