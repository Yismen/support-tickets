<div>
    <div style="height: {{ $height }};">
        <livewire:livewire-line-chart :line-chart-model="$chart" />
    </div>

    @push('scripts')
    @livewireChartsScripts
    @endpush
</div>