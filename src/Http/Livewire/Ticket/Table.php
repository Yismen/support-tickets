<?php

namespace Dainsys\Support\Http\Livewire\Ticket;

use Dainsys\Support\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\SubjectService;
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

    public function mount()
    {
        $this->table['filters']['status'] = [
            TicketStatusesEnum::Pending->value,
            TicketStatusesEnum::PendingExpired->value,
            TicketStatusesEnum::InProgress->value,
            TicketStatusesEnum::InProgressExpired->value,
        ];
    }

    public function builder(): Builder
    {
        return Ticket::query()
            ->where('created_by', '=', auth()->user()->id)
            ->addSelect([
                'created_by',
                'subject_id',
                'assigned_to',
                'assigned_at',
                'expected_at',
                'priority',
                'completed_at',
            ])
            ->with([
                'department',
                'subject',
                'owner',
                'agent',
                // 'replies'
            ])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Reference')
                ->format(fn ($value) => '#' . $value)
                ->sortable()
                ->searchable(),
            Column::make('Department', 'department.name')
                ->sortable()
                ->searchable(),
            Column::make('Subject', 'subject.name')
                ->sortable()
                ->searchable(),
            Column::make('Description')
                ->format(fn ($value, $row) => $row->short_description)
                ->sortable()
                ->searchable(),
            Column::make('Priority', 'subject.priority')
                ->format(fn ($value, $row) => "<span class='{$row->subject?->priority->class()}'> {$row->subject?->priority->name}</span>")
                ->html()
                ->searchable()
                ->sortable(),
            Column::make('Status')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> " . str($row->status->name)->headline() . '</span>')
                ->html()
                ->sortable()
                ->searchable(),
            Column::make('Assigned To', 'agent.name')
                ->format(fn ($value, $row) => $row->agent?->name)
                ->sortable()
                ->searchable(),
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
            SelectFilter::make('Subject')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    SubjectService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('subject_id', $value);
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
