<?php

namespace Dainsys\Support\Http\Livewire\Subject;

use Dainsys\Support\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Support\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Subject';

    protected $listeners = [
        'subjectUpdated' => '$refresh',
        'informationUpdated' => '$refresh',
    ];

    public function builder(): Builder
    {
        return Subject::query()
            ->with('department')
            // ->withCount([
            //     'subjects',
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
            // Column::make('Subjects', 'id')
            //     ->format(fn ($value, $row) => view('support::tables.badge')->with(['value' => $row->subjects_count])),
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
                    DepartmentService::listWithSubject()->toArray()
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
