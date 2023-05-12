<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Livewire\Component;
use Dainsys\Support\Enums\ColorsEnum;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BaseChart extends Component
{
    use AuthorizesRequests;

    public $department;
    public $height = '200px';

    protected $listeners = [
        'ticketUpdated' => '$refresh'
    ];

    protected BaseChartModel $chart;

    abstract protected function createChart();

    abstract protected function initChart(): BaseChartModel;

    public function mount($department)
    {
        $this->department = $department;
    }

    public function render()
    {
        $this->authorize('view-dashboards');

        $this->chart = $this->initChart();
        $this->chart->setTitle($this->title());
        $this->chart->withoutLegend();
        $this->chart->setDataLabelsEnabled(true);
    }

    protected function color(int $index): string
    {
        return ColorsEnum::colorIndex($index)->value;
    }

    protected function title(): string
    {
        return str(get_class($this))->beforeLast('Chart')->afterLast('\\')->headline();
    }
}
