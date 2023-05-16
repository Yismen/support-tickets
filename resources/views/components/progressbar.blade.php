@props([
'type' => 'primary',
'amount',
])

<div class="status">
    <div class="status-bar status-bar-striped bg-{{ $type }}" role="statusbar" style="width: {{ $amount }}%"
        aria-valuenow="{{ $amount }}" aria-valuemin="0" aria-valuemax="100">
        {{ $amount }}%
    </div>
</div>