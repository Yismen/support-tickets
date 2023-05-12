<div>
    <div style="height: {{ $height }};">
        <livewire:livewire-line-chart :line-chart-model="$chart" />
    </div>

    @pushOnce("scripts", "livewire-charts-script")
    @livewireChartsScripts
    @endpushOnce
</div>