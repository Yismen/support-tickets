<?php

namespace Dainsys\Support\Http\Livewire\Dashboard;

use Livewire\Component;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Services\TicketService;
use Dainsys\Support\Services\DepartmentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    public $department;

    public function mount()
    {
        if (auth()->user()->isSupportSuperAdmin()) {
            $this->selected = null;
            $this->department = is_null($this->selected)
                ? new Department()
                : Department::find($this->selected);

            return;
        }

        $this->department = auth()->user()->department;
        $this->selected = $this->department->id;
    }

    public $selected;

    public function updatedSelected($value)
    {
        if (empty($value)) {
            $this->selected = null;
        }

        $this->department = is_null($this->selected)
        ? new Department()
        : Department::find($this->selected);

        $this->emit('dashboardUpdated');
    }

    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'grabTicket'
    ];

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.dashboard.index', [
            'department' => $this->department,
            'departments' => DepartmentService::list()->toArray(),
            'total_tickets' => TicketService::byDepartment($this->selected)->count(),
            'tickets_open' => TicketService::byDepartment($this->selected)->incompleted()->count(),
            'completion_rate' => TicketService::completionRate($this->selected),
            'compliance_rate' => TicketService::complianceRate($this->selected),
        ])
        ->layout('support::layouts.app');
    }
}
