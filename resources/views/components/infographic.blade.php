@props([
'count',
'type' => 'info',
'icon' => null,
])

<div {{ $attributes->merge([
    'class' => 'info-box',
    'style' => 'max-height: 126px;'
    ]) }}>
    @if ($icon)
    <span class="bg-{{ $type }} elevation-1 info-box-icon"><i class="{{ $icon }}"></i></span>
    @endif
    <div class="info-box-content">
        <span class="m-0 p-0 text-bold text-right" style="font-size: 3rem;">
            {{ $count }}
        </span>
        <span class="font-weight-light text-uppercase overflow-hidden" title="{{ $slot }}">
            {{ $slot }}
        </span>
    </div>

</div>