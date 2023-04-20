<div>
    <livewire:support::reason.detail />
    <livewire:support::reason.form />
    <div class="d-flex justify-content-between">
        {{-- @if (count($reason_files) > 0)
        <div class="pr-2" style="max-width: 50%!important; flex: 0;">
            <h5>{{ __('support::messages.reasons_list') }}</h5>
            <ul class="list-group overflow-auto">
                @foreach ($reason_files as $reason)
                <li class="list-group-item d-flex justify-content-between ">
                    {{ $reason }}
                    <button class="btn btn-primary bg-gradient btn-sm ml-2"
                        wire:click='$emit("createReason", "{{ str($reason)->replace("\\", "\\\\") }}")'>Add</button>
                </li>
                @endforeach
            </ul>
        </div>
        @endif --}}
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <livewire:support::reason.table />
                </div>
            </div>
        </div>
    </div>
</div>