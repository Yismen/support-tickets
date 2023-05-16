@props([
'field',
'required' => true,
'options',
'placeholder' => true,
])
<div class="mb-3">
    <x-support::inputs.label :field="$field" :required="$required" :label="$slot" />

    <select wire:model='{{ $field }}' id="{{ $field }}" {{ $attributes->class([
        'form-control',
        'is-invalid' => $errors->has($field)
        ])->merge([
        ]) }}
        >
        @if ($placeholder)
        <option></option>
        @endif
        @foreach ($options as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
    </select>

    <x-support::inputs.error :field="$field" />
</div>