<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Dainsys\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\ReasonService;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Support\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Ticket';

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'informationUpdated' => '$refresh',
    ];

    public function builder(): Builder
    {
        return Ticket::query()
            ->where('created_by', '=', auth()->user()->id)
            ->addSelect([
                'created_by',
                'reason_id',
                'assigned_to',
                'assigned_at',
                'expected_at',
                'priority',
                'completed_at',
            ])
            ->with([
                'department',
                'reason',
                'owner',
                'agent',
                // 'replies'
            ])
            // ->withCount([
            //     'tickets',
            // ])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('id')
                ->sortable()
                ->searchable(),
            Column::make('Reason', 'reason.name')
                ->sortable()
                ->searchable(),
            Column::make('Department', 'department.name')
                ->sortable()
                ->searchable(),
            Column::make('Priority', 'reason.priority')
                ->format(fn ($value, $row) => "<span class='{$row->reason?->priority->class()}'> {$row->reason?->priority->name}</span>")
                ->html()
                ->searchable()
                ->sortable(),
            Column::make('Description')
                ->format(fn ($value, $row) => $row->short_description)
                ->html()
                ->sortable()
                ->searchable(),
            Column::make('Status')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> " . str($row->status->name)->headline() . '</span>')
                ->html()
                // Return from the model
                // pending: not assigned
                // in status: assigned, not completed
                // dued: assigned, not completed, time passed
                // completed in time: completed within the timeframe
                // completed outside of the timeframe
                ->sortable()
                ->searchable(),
            // Column::make('Tickets', 'id')
            //     ->format(fn ($value, $row) => view('support::tables.badge')->with(['value' => $row->tickets_count])),
            // Column::make('Agent', 'agent.name')
            //     ->sortable()
            //     ->searchable(),
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
                    DepartmentService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->whereHas('department', function ($query) use ($value) {
                        $query->where('id', $value);
                    });
                }),
            SelectFilter::make('Reason')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    ReasonService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('reason_id', $value);
                }),
            SelectFilter::make('Priority')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    TicketPrioritiesEnum::asArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('priority', $value);
                }),
            MultiSelectFilter::make('Status')
                // ->setFirstOption('All Status')
                ->options(
                    TicketStatusesEnum::asArray()
                )->filter(function (Builder $builder, array $value) {
                    $builder->whereIn('status', $value);
                }),
        ];
    }

    protected function withDefaultSorting()
    {
        $this->setDefaultSort('priority', 'desc');
    }
}
