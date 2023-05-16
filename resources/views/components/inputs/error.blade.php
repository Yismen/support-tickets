@props([
'field',
'textClass' => 'text-danger text-sm w-100'
])

@error($field)
<div {{ $attributes->merge([
    'class' => "{$textClass}"
    ]) }}>
    {{ $message }}
</div>
@enderror