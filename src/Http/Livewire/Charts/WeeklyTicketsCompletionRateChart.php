<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Enums\ColorsEnum;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\LineChartModel;
use Dainsys\Support\Services\Ticket\OldestTicketService;

class WeeklyTicketsCompletionRateChart extends BaseChart
{
    public $department;

    public function render()
    {
        parent::render();

        return view('support::livewire.charts.weekly-tickets-completion-and-compliance-rate', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new LineChartModel();
    }

    protected function createChart()
    {
        $weeks_sinces_oldest_ticket = (new OldestTicketService())->weeksSinceOldestTicket(config('support.dashboard.weeks'));

        foreach (range($weeks_sinces_oldest_ticket, 0) as $index) {
            $service = new \Dainsys\Support\Services\Ticket\CompletionService();
            $date = now()->subWeeks($index);
            $title = $date->copy()->endOfWeek()->format('Y-M-d');

            $rate = $service->weeksAgo($index, [
                'department_id' => $this->department?->id
            ]);

            $formated_value = number_format($rate * 100, 0);
            $context_color = ColorsEnum::contextColor(config('support.dashboard.context.good'), $rate);

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
