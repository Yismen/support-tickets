<?php

namespace Dainsys\Support\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

abstract class AbstractDataTableComponent extends DataTableComponent
{
    protected $create_button = true;
    protected $show_button = true;
    protected $edit_button = true;

    public function configure(): void
    {
        $records = $this->builder()->count();

        $this->withDefaultSorting();
        $this->setFilterLayoutSlideDown();

        $this->setRefreshTime(config('support.polling_miliseconds'));

        $this->setPrimaryKey('id');
        $this->setColumnSelectDisabled();
        $this->setQueryStringDisabled();
        $this->setTableAttributes([
            'class' => 'table-sm table-hover',
        ]);

        $this->setConfigurableAreas([
            'before-toolbar' => [
                'support::tables.header', [
                    'count' => $records,
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getTheme(): string
    {
        return 'bootstrap-4';
    }

    public function applySearch(): Builder
    {
        if ($this->searchIsEnabled() && $this->hasSearch()) {
            $searchableColumns = $this->getSearchableColumns();

            if ($searchableColumns->count()) {
                $this->setBuilder($this->getBuilder()->where(function ($query) use ($searchableColumns) {
                    $searchTerms = preg_split("/[\s]+/", $this->getSearch(), -1, PREG_SPLIT_NO_EMPTY);

                    foreach ($searchTerms as $value) {
                        $query->where(function ($query) use ($searchableColumns, $value) {
                            foreach ($searchableColumns as $index => $column) {
                                if ($column->hasSearchCallback()) {
                                    ($column->getSearchCallback())($query, $this->getSearch());
                                } else {
                                    $query->{$index === 0 ? 'where' : 'orWhere'}($column->getColumn(), 'like', '%' . $value . '%');
                                }
                            }
                        });
                    }
                }));
            }
        }

        return $this->getBuilder();
    }

    /**
     * @return self
     */
    public function hiddenFromAll(bool $value): self
    {
        dd('asdfsdf');
        $this->hiddenFromMenus = true;
        $this->hiddenFromPills = true;
        $this->hiddenFromFilterCount = true;

        return $this;
    }

    protected function withDefaultSorting()
    {
        $this->setDefaultSort('name', 'asc');
    }

    public function disableCreateButton()
    {
        $this->create_button = false;
    }

    public function disableEditButton()
    {
        $this->edit_button = false;
    }

    public function disableShowButton()
    {
        $this->show_button = false;
    }

    protected function tableTitle(): string
    {
        return str($this->module)->headline()->plural() . ' ' . __('support::messages.table');
    }
}
