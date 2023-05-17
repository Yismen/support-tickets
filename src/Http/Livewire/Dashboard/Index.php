<?php

namespace Dainsys\Support\Http\Livewire\Dashboard;

use Livewire\Component;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Services\TicketService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dainsys\Support\Services\Department\DepartmentListService;

class Index extends Component
{
    use AuthorizesRequests;

    public $department;
    public $selected;
    protected $listeners = [
        'ticketUpdated' => '$refresh',
        'grabTicket'
    ];

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

    public function render()
    {
        $this->authorize('view-dashboards');

        return view('support::livewire.dashboard.index', [
            'department' => $this->department,
            'departments' => DepartmentListService::withTicketsOnly()->pluck('name', 'id')->toArray(),
            'total_tickets' => TicketService::byDepartment($this->selected)->count(),
            'tickets_open' => TicketService::byDepartment($this->selected)->incompleted()->count(),
            'completion_rate' => TicketService::completionRate($this->selected),
            'compliance_rate' => TicketService::complianceRate($this->selected),
            'satisfaction_rate' => TicketService::satisfactionRate($this->selected),
        ])
        ->layout('support::layouts.app');
    }

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
}
