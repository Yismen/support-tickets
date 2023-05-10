<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Services\TicketService;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class WeeklyTiicketsCountChart extends BaseChart
{
    public $department;

    public function mount($department)
    {
        $this->department = $department;
    }

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.charts.weekly-tickets-count', [
            'chart' => $this->createChart()
        ]);
    }

    protected function createChart()
    {
        $chart = new ColumnChartModel();
        $chart->setTitle('Weekly Total Tickets');
        $chart->withoutLegend();

        foreach (range(12, 0) as $index) {
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('Y-M-d');
            $service = new TicketService();

            $chart->addColumn(
                $title,
                $service->countWeeksAgo($index, [
                    'department_id' => $this->department?->id,
                ]),
                $this->color($index)
            );
        }

        return $chart;
    }
}
