@props([
'editing',
'footer' => true,
])

<form @if ($editing) wire:submit.prevent="update()" @else wire:submit.prevent="store()" @endif {{ $attributes->merge([
    'class' => 'needs-validation'
    ]) }} autocomplete="off">

    {{ $slot }}

    @if($footer)
    <div class="mt-3 border-top p-2">

        @if ($editing)
        <x-support::button type="submit" color="warning" class="btn-sm">
            {{ __('support::messages.update') }}
        </x-support::button>
        @else
        <x-support::button type="submit" color="primary" class="btn-sm">
            {{ __('support::messages.create') }}
        </x-support::button>
        @endif
    </div>
    @endif
</form>