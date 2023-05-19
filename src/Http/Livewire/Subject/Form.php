<?php

namespace Dainsys\Support\Http\Livewire\Subject;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Dainsys\Support\Models\Subject;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Enums\TicketPrioritiesEnum;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;

    protected $listeners = [
        'createSubject',
        'updateSubject',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showSubjectFormModal';
    public $subjects = [];

    public $subject;

    public function render()
    {
        return view('support::livewire.subject.form', [
            'departments' => DepartmentService::list(),
            'priorities' => TicketPrioritiesEnum::asArray(),
        ])
        ->layout('support::layouts.app');
    }

    public function createSubject($subject = null)
    {
        $this->subject = new Subject();
        $this->authorize('create', $this->subject);
        $this->editing = false;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function updateSubject(Subject $subject)
    {
        $this->subject = $subject;
        $this->authorize('update', $this->subject);
        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function store()
    {
        $this->authorize('create', new Subject());
        $this->validate();

        $this->editing = false;

        $this->subject->save();

        supportFlash('Subject created!', 'success');

        $this->dispatchBrowserEvent('closeAllModals');

        $this->emit('subjectUpdated');
    }

    public function update()
    {
        $this->authorize('update', $this->subject);
        $this->validate();

        $this->subject->save();

        supportFlash('Subject updated!', 'success');

        $this->dispatchBrowserEvent('closeAllModals');

        $this->editing = false;

        $this->emit('subjectUpdated');
    }

    protected function getRules()
    {
        return [
            'subject.name' => [
                'required',
                Rule::unique(supportTableName('subjects'), 'name')->ignore($this->subject->id ?? 0)
            ],
            'subject.department_id' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'subject.priority' => [
                'required',
                Rule::in(array_column(TicketPrioritiesEnum::cases(), 'value'))
            ],
            'subject.description' => [
                'nullable'
            ]
        ];
    }
}
