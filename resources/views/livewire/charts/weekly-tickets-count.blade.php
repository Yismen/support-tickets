<div>
    <div style="height: {{ $height }};">
        <livewire:livewire-column-chart :column-chart-model="$chart" />
    </div>

    @pushOnce('scripts')
    @livewireChartsScripts
    @endpushOnce
</div>