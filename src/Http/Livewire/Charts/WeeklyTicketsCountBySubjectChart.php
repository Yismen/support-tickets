<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Dainsys\Support\Models\Subject;
use Illuminate\Support\Facades\Cache;
use Asantibanez\LivewireCharts\Models\BaseChartModel;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;

class WeeklyTicketsCountBySubjectChart extends BaseChart
{
    public function render()
    {
        parent::render();

        return view('support::livewire.charts.weekly-tickets-count-by-subject', [
            'chart' => $this->createChart()
        ]);
    }

    protected function initChart(): BaseChartModel
    {
        return new ColumnChartModel();
    }

    protected function createChart()
    {
        $subjects = Cache::rememberForever('tickets-count-by-subjects-' . $this->department->id, function () {
            return Subject::query()
                ->withCount('tickets')
                ->orderBy('tickets_count', 'desc')
                ->when($this->department->id ?? null, function ($query) {
                    $query->where('department_id', $this->department->id);
                })
                ->take(10)
                // ->having('tickets_count', '>', 0)
                ->get()
                ->filter(fn ($subject) => $subject->tickets_count > 0);
        });

        $subjects->each(function ($subject, $index) {
            $this->chart->addColumn(
                $subject->name,
                $subject->tickets_count,
                $this->color($index)
            );
        });

        return $this->chart;
    }
}
