<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class WeeklyTiicketsChart extends BaseChart
{
    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.charts.weekly-tickets', [
            'chart' => $this->createChart()
        ]);
    }

    protected function createChart()
    {
        $chart = new ColumnChartModel();
        $chart->setTitle('Weekly Total Tickets');

        Cache::flush();
        foreach (range(8, 0) as $index) {
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('y-M-d');

            $chart->addColumn(
                $title,
                Cache::rememberForever('weekly-tickets-count' . $title, function () use ($date) {
                    return Ticket::query()
                    ->whereDate('created_at', '>=', $date->copy()->startOfWeek())
                    ->whereDate('created_at', '<=', $date->copy()->endOfWeek())
                    ->count();
                }),
                $this->color($index)
            );
        }

        return $chart
            ->withoutLegend();
        // ->addColumn('Food', 100, '#f6ad55')
                // ->addColumn('Shopping', 200, '#fc8181')
                // ->addColumn('Travel', 300, '#90cdf4')
                // ->addSeriesColumn('Weekly Total Tickets', 'title', 150.45, [45, 50])
    }
}
