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
            {{ str(__('support::messages.update'))->upper() }}
        </x-support::button>
        @else
        <x-support::button type="submit" color="primary" class="btn-sm">
            {{ str(__('support::messages.create'))->upper() }}
        </x-support::button>
        @endif
    </div>
    @endif
</form>