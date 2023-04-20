<?php

namespace Dainsys\Support\Http\Livewire\Reason;

use Livewire\Component;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Services\ReasonService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'reasonUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->authorize('viewAny', new Reason());

        return view('support::livewire.reason.index', [
            'reasons' => ReasonService::list()
        ])
        ->layout('support::layouts.app');
    }
}
