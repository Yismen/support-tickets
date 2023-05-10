<div>
    <div style="height: {{ $height }};">
        <livewire:livewire-column-chart :column-chart-model="$chart" />
    </div>

    @push('scripts')
    @livewireChartsScripts
    @endpush
</div>