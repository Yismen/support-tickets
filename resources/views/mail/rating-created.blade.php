@component('mail::message')
# Hello,

{{ $user->name }} has rated service on ticket #{{ $rating->ticket->reference }}@if ($rating->ticket->agent), attended
by **{{ $rating->ticket->agent->name
}}**, @endif as **{{ str($rating->score->name)->headline() }}**.

@if ($rating->comment)
Comment:
> "*{{ $rating->comment }}*"
@endif

@component('mail::button', ['url' => route('support.my_tickets', ['ticket_details' => $rating->ticket->id])])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent