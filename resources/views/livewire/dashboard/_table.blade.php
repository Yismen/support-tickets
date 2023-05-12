<div class="card">
    <div class="card-body">
        <livewire:support::dashboard.table :department='$department'
            wire:key="department-table-{{ $department?->id ?? null }}" />
    </div>
</div>