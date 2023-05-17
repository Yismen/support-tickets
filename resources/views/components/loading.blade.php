@props([
'target' => null,
'removeWhileLoading' => true,
'loadingText'
])
<div class="w-100">
    <div wire:loading @if ($target) wire:target='{{ $target }}' @endif {{ $attributes->merge([
        'class' => 'w-100 loading'
        ]) }}>
        @foreach (range(1, 4) as $val)
        @php
        $width = rand(25, 95);
        @endphp
        <div style="width: {{ $width }}%;" class="loading-field my-1" id="loading-{{ $val }}"></div>
        @endforeach
    </div>

    <div @if ($removeWhileLoading) wire:loading.remove @endif @if ($target) wire:target='{{ $target }}' @endif>
        {{ $slot }}
    </div>

    @pushOnce('styles', 'loading-style')
    <style>
        :root {
            --loading-grey: rgba(218, 218, 218, 0.6);
        }

        .loading::after {
            content: "";
            position: absolute;
            background-color: rgba(240, 240, 240, .1);
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .loading-field {
            font-size: 1.5rem;
            line-height: 1.5rem;
            height: 1.5rem;
            /* background: var(--loading-grey) */
            background: linear-gradient(110deg, var(--loading-grey) 8%, #f5f5f5 18%, var(--loading-grey) 33%);
            color: var(--loading-grey);
            animation: 1.5s animate-background ease-in-out infinite;
            background-size: 200% 100%;
        }

        @keyframes animate-background {
            to {
                background-position-x: -200%;
            }
        }
    </style>
    @endpushOnce
</div>