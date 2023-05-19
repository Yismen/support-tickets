@can('assign-ticket', $ticket)
<div class="bg-teal mt-2 p-2 d-block">
    <x-support::inputs.select field="assign_to" :options='$team'>
        Assign Ticket To:
    </x-support::inputs.select>
</div>
@endcan