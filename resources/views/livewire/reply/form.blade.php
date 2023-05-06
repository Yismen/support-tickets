<div>
    @can('create', [$reply, $ticket])
    <x-support::loading target="store,update" content="Updating Reply..." />
    <form wire:submit.prevent="{{ $editing ? 'update' : 'store' }}" wire:loading.remove>
        <div class="form-group p-2">
            <x-support::inputs.text-area :field="'reply.content'" rows="2" modifier=".{{ $modifier }}">
                Leave a Reply
            </x-support::inputs.text-area>

            <div class="d-flex justify-content-between">
                <button type="submit" class="text-uppercase btn btn-xs btn-{{ $editing ? 'warning' : 'primary' }}">
                    @if ($editing)
                    {{ __('support::messages.update') }}
                    @else
                    {{ __('support::messages.create') }}
                    @endif
                </button>

                @if ($editing)
                <button wire:click.prevent='cancel' class="btn btn-secondary btn-xs">Cancel</button>
                @endif
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('updateReply', function(e) {
            let element = document.getElementById('reply.content');

            element.scrollIntoView({ behavior: 'smooth', block: 'end'});

            element.focus()
        });
    </script>
    @endpush
    @endcan
</div>