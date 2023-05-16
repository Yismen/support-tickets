@component('mail::message')
# Hello,

A new ticket with #{{ $ticket->reference }} has been created by {{ $ticket->owner->name }} form department {{
$ticket->department->name }}!

**Title: {{ $ticket->reason->name }}**

*Content:*
> {!! $ticket->description !!}

@component('mail::button', ['url' => url('/support/my_tickets')])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent