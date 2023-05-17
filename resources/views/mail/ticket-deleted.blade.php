@component('mail::message')
# Hello,

Ticket #{{ $ticket->reference }}, created by {{ $ticket->owner->name }} {{ $ticket->created_at->diffForHumans() }}, has
been deleted by {{ $user?->name }}.

**Title: {{ $ticket->reason->name }}**

*Content:*
> {!! $ticket->description !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent