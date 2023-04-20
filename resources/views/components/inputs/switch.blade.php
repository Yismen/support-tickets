@props([
'field',
'required' => true,
'value'
])

@php
$id = $value ?? rand();
@endphp
<div class="custom-control custom-switch">
    <input type="checkbox" id="{{ $field }}-{{ $id }}" value="{{ $id }}" wire:model='{{ $field }}' {{
        $attributes->class([
    'custom-control-input',
    'is-invalid' => $errors->has($field)
    ])->merge([])
    }} style="cursor: pointer;">
    <label class="custom-control-label" for="{{ $field }}-{{ $id }}" style="cursor: pointer;">
        {{ $slot }}
    </label>
    <x-support::inputs.error :field="$field" />
</div>