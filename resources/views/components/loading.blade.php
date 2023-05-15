@props([
'target' => null,
'removeWhileLoading' => false,
'loadingText'
])
<div class="w-100">
    <div wire:loading @if ($target) wire:target='{{ $target }}' @endif {{ $attributes->merge([
        'class' => 'w-100 loading'
        ]) }}>
        @foreach (range(1, 3) as $val)
        @php
        $width = rand(65, 95);
        @endphp
        <div style="width: {{ $width }}%;" class="loading-field my-1" id="loading-{{ $val }}">c</div>
        @endforeach
    </div>

    <div @if ($removeWhileLoading) wire:loading.remove @endif @if ($target) wire:target='{{ $target }}' @endif>
        {{ $slot }}
    </div>

    @pushOnce('styles', 'loading-style')
    <style>
        :root {
            --loading-grey: rgba(229, 229, 229, .6);
        }

        .loading {
            /* background: #eee; */
            /* background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
            border-radius: 5px;
            background-size: 200% 100%;
            animation: 1.5s animate-background linear infinite; */
        }

        .loading-field {
            font-size: 1.4rem;
            line-height: 1.4rem;
            /* background: var(--loading-grey) */
            background: linear-gradient(110deg, var(--loading-grey) 8%, #f5f5f5 18%, var(--loading-grey) 33%);
            color: var(--loading-grey);
            animation: 1.5s animate-background ease-in-out infinite;
        }

        @keyframes animate-background {
            to {
                background-position-x: -200%;
            }
        }
    </style>
    @endpushOnce
</div>