@props([
'target' => null,
'content'
])
<div wire:loading @if ($target) wire:target='{{ $target }}' @endif {{ $attributes->merge([
    'class' => 'p-4 bg-light'
    ]) }}>
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <span class="visually-hidden">{{ $content ?? 'Loading...' }}</span>
</div>