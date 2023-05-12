<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Models\Reason;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Services\TicketService;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class WeeklyTicketsCountByReasonChart extends BaseChart
{
    public function render()
    {
        parent::render();

        return view('support::livewire.charts.weekly-tickets-count-by-reason', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new ColumnChartModel();
    }

    protected function createChart()
    {
        $reasons = Cache::rememberForever('tickets-count-by-reasons-' . $this->department->id, function () {
            return Reason::query()
                ->withCount('tickets')
                ->orderBy('tickets_count', 'desc')
                ->when($this->department->id ?? null, function ($query) {
                    $query->where('department_id', $this->department->id);
                })
                ->take(10)
                // ->having('tickets_count', '>', 0)
                ->get()
                ->filter(fn ($reason) => $reason->tickets_count > 0);
        });

        $reasons->each(function ($reason, $index) {
            $this->chart->addColumn(
                $reason->name,
                $reason->tickets_count,
                $this->color($index)
            );
        });

        return $this->chart;
        // foreach (range(12, 0) as $index) {
        //     $date = now()->subWeeks($index);
        //     $title = $date->copy()->endOfWeek()->format('Y-M-d');
        //     $service = new TicketService();

        //     $this->chart->addColumn(
        //         $title,
        //         $service->countWeeksAgo($index, [
        //             'department_id' => $this->department?->id,
        //         ]),
        //         $this->color($index)
        //     );
        // }

        // return $this->chart;
    }
}
