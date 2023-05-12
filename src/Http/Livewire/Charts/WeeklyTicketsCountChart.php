<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Services\TicketService;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Dainsys\Support\Services\Ticket\OldestTicketService;

class WeeklyTicketsCountChart extends BaseChart
{
    public function render()
    {
        parent::render();

        return view('support::livewire.charts.weekly-tickets-count', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new ColumnChartModel();
    }

    protected function createChart()
    {
        $weeks_sinces_oldest_ticket = (new OldestTicketService())->weeksSinceOldestTicket(config('support.dashboard.weeks'));

        foreach (range($weeks_sinces_oldest_ticket, 0) as $index) {
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('Y-M-d');
            $service = new TicketService();

            $this->chart->addColumn(
                $title,
                $service->countWeeksAgo($index, [
                    'department_id' => $this->department?->id,
                ]),
                $this->color($index)
            );
        }

        return $this->chart;
    }
}
