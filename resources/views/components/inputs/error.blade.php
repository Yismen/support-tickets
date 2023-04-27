@props([
'field',
'textClass' => 'invalid-feedback'
])

@error($field)
<div {{ $attributes->merge([
    'class' => "{$textClass}"
    ]) }}>
    {{ $message }}
</div>
@enderror