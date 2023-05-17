@component('mail::message')
# Hello,

{{ $user?->name }} has replied on ticket #{{ $reply->ticket->reference }} with the following message:

> *"{{ $reply->content }}"*

@component('mail::button', ['url' => route('support.my_tickets', ['ticket_details' => $reply->ticket->id])])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent