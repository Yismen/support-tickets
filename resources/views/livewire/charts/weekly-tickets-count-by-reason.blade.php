<div>
    <div style="height: {{ $height }};">
        <livewire:livewire-column-chart :column-chart-model="$chart" />
    </div>

    @pushOnce("scripts", "livewire-charts-script")
    @livewireChartsScripts
    @endpushOnce
</div>