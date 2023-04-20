<?php

namespace Dainsys\Support\Http\Livewire\Reason;

use Livewire\Component;
use Dainsys\Support\Models\Reason;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Detail extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'showReason',
    ];

    public bool $editing = false;
    public string $modal_event_name_detail = 'showReasonDetailModal';

    public $reason;

    public function render()
    {
        // $this->authorize('view', $this->reason);
        // dd($this->reason);

        return view('support::livewire.reason.detail')
        ->layout('support::layouts.app');
    }

    public function showReason(Reason $reason)
    {
        $this->authorize('view', $reason);

        $this->editing = false;
        $this->reason = $reason;
        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_detail);
    }
}
