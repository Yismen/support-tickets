@component('mail::message')
# Hello,

The following tickets are expired and have not been completed:

| Ticket | Owner | Subject | Department | Assigned To | Created At | Expired At |
| ------ | ----- | ------- | ---------- | ----------- |----------- | ---------- |
@foreach ($tickets as $ticket)
| {{ $ticket->reference }} | {{ $ticket->owner?->name }} | {{ $ticket->subject->name }} | {{ $ticket->department->name }} | {{ $ticket->agent?->name }} | {{ $ticket->created_at?->diffForHumans() }} | {{ $ticket->expected_at?->diffForHumans() }} |
@endforeach


<x-support::email.button :url="route('support.dashboard')"> Dashboard </x-support::email.button>

Thanks,<br>
{{ config('app.name') }}
@endcomponent