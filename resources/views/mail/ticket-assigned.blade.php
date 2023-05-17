@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, has
been assigned to **{{ $ticket->agent->name }}** by {{ $user?->name }}.

This ticket {{ $ticket->expected_at->isPast() ? 'was' : 'is' }} expected {{ $ticket->expected_at->diffForHumans() }}

**Title: {{ $ticket->reason->name }}**

*Content:*
> {!! $ticket->description !!}

@component('mail::button', ['url' => route('support.my_tickets', ['ticket_details' => $ticket->id])])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent