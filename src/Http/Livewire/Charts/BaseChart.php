<?php

namespace Dainsys\Support\Http\Livewire\Charts;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class BaseChart extends Component
{
    use AuthorizesRequests;

    public $height = '200px';
    protected $colors = [
        '#FFC107', //(Amber)
        '#9C27B0', //(Purple)
        '#795548', //(Brown)
        '#E91E63', //(Pink)
        '#00BCD4', //(Cyan)
        '#009688', //(Teal)
        '#3F51B5', //(Indigo)
        '#4CAF50', //(Green)
        '#673AB7', //(Deep Purple)
        '#FFEB3B', //(Yellow)
        '#FF5722', //(Deep Orange)
        '#607D8B', //(Blue Grey)
        '#2196F3', //(Blue)
        '#FF9800', //(Orange)
        '#9E9E9E', //(Grey)
        '#FFCDD2', //(Pink-50)
        '#8BC34A', //(Light Green)
        '#E0F2F1', //(Teal-50)
        '#C8E6C9', //(Green-50)
        '#FFE0B2', //(Orange-50)
    ];

    abstract public function render();

    abstract protected function createChart();

    protected function color(int $index)
    {
        return $this->colors[$index] ?? $this->colors[array_rand($this->colors)]
        ;
    }
}
