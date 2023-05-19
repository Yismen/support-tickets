<?php

namespace Dainsys\Support\Http\Livewire\Subject;

use Livewire\Component;
use Dainsys\Support\Models\Subject;
use Dainsys\Support\Services\SubjectService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'subjectUpdated' => '$refresh',
    ];

    public function render()
    {
        $this->authorize('viewAny', new Subject());

        return view('support::livewire.subject.index', [
            'subjects' => SubjectService::list()
        ])
        ->layout('support::layouts.app');
    }
}
