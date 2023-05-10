<?php

namespace Dainsys\Support\Http\Livewire\Dashboard;

use App\Models\User;
use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\ReasonService;
use Dainsys\Support\Enums\TicketStatusesEnum;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Support\Services\UserDepartmentRoleService;
use Dainsys\Support\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'Ticket';
    protected $create_button = false;
    protected $edit_button = false;

    public $department;

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'dashboardUpdated' => '$refresh',
    ];

    public function mount(Department $department)
    {
        $this->department = $department;
    }

    public function builder(): Builder
    {
        return Ticket::query()
            ->when(
                auth()->user()->departmentRole?->role === DepartmentRolesEnum::Agent,
                function ($query) {
                    $query->incompleted();
                }
            )
            ->when($this->department->id, function ($query) {
                $query->whereHas('department', function ($query) {
                    $query->where('id', $this->department->id);
                });
            })
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
                'agent',
                'owner',
                // 'replies'
            ])
            // ->withCount([
            //     'tickets',
            // ])
            ;
    }

    // public function bulkActions(): array
    // {
    //     parent::bulkActions();

    //     if (auth()->user()->isDepartmentAdmin($this->department)) {
    //         return [
    //             'assignMany' => 'Assign'
    //         ];
    //     }
    // }

    // public function assignMany()
    // {
    //     $records = Ticket::findMany($this->getSelected());

    //     $this->clearSelected();
    // }

    public function columns(): array
    {
        return [

            Column::make('id')
                ->sortable()
                ->searchable(),
            Column::make('Department', 'department.name')
                ->hideIf(!is_null($this->department->id))
                ->sortable()
                ->searchable(),
            Column::make('Reason', 'reason.name')
                ->sortable()
                ->searchable(),
            // Column::make('Description')
            //     ->format(fn ($value, $row) => $row->short_description)
            //     ->html()
            //     ->sortable()
            //     ->searchable(),
            Column::make('Owner', 'owner.name')
                ->sortable()
                ->searchable(),
            Column::make('Priority', 'reason.priority')
                ->format(fn ($value, $row) => "<span class='{$row->reason?->priority->class()}'> {$row->reason?->priority->name}</span>")
                ->html()
                ->searchable()
                ->sortable(),
            Column::make('Status')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> " . str($row->status->name)->headline() . '</span>')
                ->html()
                ->sortable()
                ->searchable(),
            Column::make('Expected At')
                ->format(fn ($value, $row) => "<span class='{$row->status->class()}'> {$row->expected_at?->diffForHumans()}</span>")
                ->html()
                ->sortable(),
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
            SelectFilter::make('Reason')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    ReasonService::list()->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('reason_id', $value);
                }),
            SelectFilter::make('Assigned To')
                ->options(
                    [
                        '' => 'All',

                    ] +
                    UserDepartmentRoleService::list($this->department)->toArray()
                )->filter(function (Builder $builder, int $value) {
                    $builder->where('assigned_to', $value);
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
            SelectFilter::make('Completion Status')
                ->options(
                    [
                        '' => 'All',
                        'completed' => 'Completed',
                        'incompleted' => 'No Completed',

                    ]
                )->filter(function (Builder $builder, string $value) {
                    $builder->$value();
                }),
            // MultiSelectFilter::make('Status')
            //     ->options(
            //         TicketStatusesEnum::asArray()
            //     )
            //     ->filter(function (Builder $builder, array $values) {
            //         $builder->whereIn('status', $values);
            //     })
        ];
    }

    protected function withDefaultSorting()
    {
        $this->setDefaultSort('expected_at', 'asc');
    }

    protected function tableTitle(): string
    {
        return str(join(' ', [
            __('support::messages.tickets'),
            __('support::messages.for'),
            $this->department->name ?? 'All Departments'
        ]))->headline();
    }
}
