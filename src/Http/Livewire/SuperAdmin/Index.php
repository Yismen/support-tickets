<?php

namespace Dainsys\Support\Http\Livewire\Department;

use Livewire\Component;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Services\DepartmentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'departmentUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->authorize('viewAny', new Department());

        return view('support::livewire.department.index', [
            'departments' => DepartmentService::list()
        ])
        ->layout('support::layouts.app');
    }
}
