<?php

namespace Dainsys\Support\Http\Livewire\Subject;

use Livewire\Component;
use Dainsys\Support\Models\Subject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Detail extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'showSubject',
    ];

    public bool $editing = false;
    public string $modal_event_name_detail = 'showSubjectDetailModal';

    public $subject;

    public function render()
    {
        return view('support::livewire.subject.detail')
        ->layout('support::layouts.app');
    }

    public function showSubject(Subject $subject)
    {
        $this->authorize('view', $subject);

        $this->editing = false;
        $this->subject = $subject;
        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_detail);
    }
}
