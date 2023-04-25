@props([
'field',
'required' => true,
'options',
'placeholder' => true,
])
<div class="mb-3 ">
    <x-support::inputs.label :field="$field" :required="$required" :label="$slot" />

    @foreach ($options as $key => $value)
    <div {{ $attributes->class([
        'form-check',
        'is-invalid' => $errors->has($field)
        ])->merge([
        ]) }}>
        <input class="form-check-input" type="radio" wire:model='{{ $field }}' id="{{  $field }}-{{ $key }}"
            value="{{ $key }}">
        <label class="form-check-label" for="{{ $field }}-{{ $key }}">
            {{ $value }}
        </label>
    </div>
    @endforeach
    <x-support::inputs.error :field="$field" />
</div>