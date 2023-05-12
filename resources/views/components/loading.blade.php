@props([
'target' => null,
'removeWhileLoading' => false,
'loadingText'
])
<div>
    <div wire:loading @if ($target) wire:target='{{ $target }}' @endif {{ $attributes->merge([
        'class' => 'p-4 bg-light'
        ]) }}>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
        <span class="visually-hidden">{{ $loadingText ?? 'Loading...' }}</span>
    </div>

    <div @if ($removeWhileLoading) wire:loading.remove @endif @if ($target) wire:target='{{ $target }}' @endif>
        {{ $slot }}
    </div>
</div>