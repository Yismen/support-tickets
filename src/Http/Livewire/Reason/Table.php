<?php

namespace Dainsys\Support\Http\Livewire\Reason;

use Dainsys\Support\Models\Reason;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Support\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Reason';

    protected $listeners = [
        'reasonUpdated' => '$refresh',
        'informationUpdated' => '$refresh',
    ];

    public function builder(): Builder
    {
        return Reason::query()
            ->with('department')
            // ->withCount([
            //     'reasons',
            // ])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Department', 'department.name')
                ->sortable()
                ->searchable(),
            Column::make('Priority')
                ->format(fn ($value, $row) => "<span class='{$row->priority->class()}'> {$row->priority->name}</span>")
                ->html()
                ->searchable()
                ->sortable(),
            Column::make('Description')
                ->format(fn ($value, $row) => $row->short_description)
                ->sortable()
                ->searchable(),
            // Column::make('Reasons', 'id')
            //     ->format(fn ($value, $row) => view('support::tables.badge')->with(['value' => $row->reasons_count])),
            Column::make('Actions', 'id')
                ->view('support::tables.actions'),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Department')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    DepartmentService::listWithReason()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('department_id', $value);
                }),
        ];
    }

    protected function withDefaultSorting()
    {
        $this->setDefaultSort('name', 'asc');
    }
}
