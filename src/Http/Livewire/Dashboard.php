<?php

namespace Dainsys\Support\Http\Livewire;

use Livewire\Component;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Services\DepartmentFilesService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Dashboard extends Component
{
    use AuthorizesRequests;

    public function render()
    {
        // $this->authorize('interact-with-support-admin');

        return view('support::livewire.dashboard', [
            // 'departments' => DepartmentFilesService::count(),
            // 'registered' => DepartmentService::count(),
            // 'departments' => DepartmentService::count(),
            'user' => auth()->user()
        ])
        ->layout('support::layouts.app');
    }
}
