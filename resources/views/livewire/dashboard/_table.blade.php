<div class="card">
    <div class="card-body">
        {{-- <x-support::loading> --}}
            <livewire:support::dashboard.table :department='$department'
                wire:key="department-table-{{ $department?->id ?? null }}" />
            {{--
        </x-support::loading> --}}
    </div>
</div>