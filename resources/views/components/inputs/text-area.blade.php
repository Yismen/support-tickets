@props([
'field',
'required' => true,
])

<div class="mb-3">
    <x-support::inputs.label :field="$field" :required="$required" :label="$slot" />
    <textarea wire:model='{{ $field }}' {{ $attributes->class([
            'form-control',
            'is-invalid' => $errors->has($field)
        ])->merge([
            'rows' => 5,
            'id' => $field 
        ]) }}
        ></textarea>

    <x-support::inputs.error :field="$field" />
</div>