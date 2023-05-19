<?php

namespace Dainsys\Support\Http\Livewire\DepartmentRole;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Services\DepartmentService;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Dainsys\Support\Http\Livewire\AbstractDataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class Table extends AbstractDataTableComponent
{
    protected string $module = 'DepartmentRole';

    protected $listeners = [
        'departmentUpdated' => '$refresh',
        'informationUpdated' => '$refresh',
    ];

    public function configure(): void
    {
        parent::configure();

        $this->disableCreateButton();
        $this->disableShowButton();

        $this->setTrAttributes(function ($row, $index) {
            if ($row->departmentRole?->role->value === DepartmentRolesEnum::Admin->value) {
                return [
                    'default' => true,
                    'class' => 'text-success text-bold',
                ];
            }

            if ($row->departmentRole?->role->value === DepartmentRolesEnum::Agent->value) {
                return [
                    'default' => true,
                    'class' => 'text-danger text-bold',
                ];
            }

            return ['default' => true];
        });
    }

    public function builder(): Builder
    {
        return User::query()
            ->where(resolve(User::class)->getTable() . '.id', '!=', auth()->user()->id)
            ->whereDoesntHave('supportSuperAdmin')
            ->with('departmentRole.department')
            // ->withCount([
            //     'departments',
            // ])
            ;
    }

    public function columns(): array
    {
        return [
            Column::make('Name')
                ->sortable()
                ->searchable(),
            Column::make('Email')
                ->sortable()
                ->searchable(),
            Column::make('Department', 'departmentRole.department.name')
                ->sortable()
                ->searchable(),
            Column::make('Role', 'departmentRole.role')
                ->sortable()
                ->searchable(),
            // Column::make('DepartmentRoles', 'id')
            //     ->format(fn ($value, $row) => view('support::tables.badge')->with(['value' => $row->departments_count])),
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
                    $builder->where('department_id', $value);
                }),
        ];
    }
}
