<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Enums\ColorsEnum;
use Dainsys\Support\Services\TicketService;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;

class WeeklyTicketsCompletionRateChart extends BaseChart
{
    public $department;

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.charts.weekly-tickets-completion-rate', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new LineChartModel();
    }

    protected function createChart()
    {
        foreach (range(12, 0) as $index) {
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('Y-M-d');
            $service = new TicketService();
            $rate = $service->completionWeeksAgo($index, [
                'department_id' => $this->department?->id
            ]);

            $formated_value = number_format($rate * 100, 0);
            $context_color = ColorsEnum::contextColor(0.8, $rate);

            $this->chart
                ->setDataLabelsEnabled(false)
                ->addMarker(
                    $title,
                    $formated_value,
                    $context_color,
                    $formated_value,
                    ColorsEnum::WHITE,
                    $context_color
                )
                ->addPoint(
                    $title,
                    $formated_value,
                    $context_color
                );
        }

        return $this->chart;
    }
}
