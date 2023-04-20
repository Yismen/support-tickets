<?php

namespace Dainsys\Support\Http\Livewire\Reason;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Reason;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;

    protected $listeners = [
        'createReason',
        'updateReason',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showReasonFormModal';
    public $reasons = [];

    public $reason;

    public function render()
    {
        return view('support::livewire.reason.form', [
            'departments' => DepartmentService::list()
        ])
        ->layout('support::layouts.app');
    }

    public function createReason($reason = null)
    {
        $this->reason = new Reason();
        $this->authorize('create', $this->reason);
        $this->editing = false;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function updateReason(Reason $reason)
    {
        $this->reason = $reason;
        $this->authorize('update', $this->reason);
        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function store()
    {
        $this->authorize('create', new Reason());
        $this->validate();

        $this->editing = false;

        $this->reason->save();

        $this->dispatchBrowserEvent('closeAllModals');

        $this->emit('reasonUpdated');

        flasher('Reason created!', 'success');
    }

    public function update()
    {
        $this->authorize('update', $this->reason);
        $this->validate();

        $this->reason->save();

        $this->dispatchBrowserEvent('closeAllModals');

        $this->editing = false;

        $this->emit('reasonUpdated');

        flasher('Reason updated!', 'success');
    }

    protected function getRules()
    {
        return [
            'reason.name' => [
                'required',
                Rule::unique(supportTableName('reasons'), 'name')->ignore($this->reason->id ?? 0)
            ],
            'reason.department_id' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'reason.description' => [
                'nullable'
            ]
        ];
    }
}
