<?php

namespace Dainsys\Support\Http\Livewire\DepartmentRole;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Services\DepartmentService;
use Dainsys\Support\Traits\WithRealTimeValidation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;
    use WithRealTimeValidation;

    protected $listeners = [
        'updateDepartmentRole',
    ];

    public bool $editing = false;
    public string $modal_event_name_form = 'showDepartmentRoleFormModal';

    public $departments;
    public $roles;

    public $department;
    public $role;
    public $user;

    public function mount()
    {
        $this->departments = DepartmentService::list();
        $this->roles = DepartmentRolesEnum::asArray();
    }

    public function render()
    {
        return view('support::livewire.department_role.form')
        ->layout('support::layouts.app');
    }

    public function updateDepartmentRole(User $user)
    {
        $this->reset(['department', 'user', 'role']);
        $this->department = optional($user->departmentRole)->department_id;
        $this->role = optional($user->departmentRole)->role;

        $this->user = $user;
        $this->authorize('update', new DepartmentRole());
        $this->editing = true;

        $this->resetValidation();

        $this->dispatchBrowserEvent('closeAllModals');
        $this->dispatchBrowserEvent($this->modal_event_name_form);
    }

    public function update()
    {
        $this->authorize('update', new DepartmentRole());
        $this->validate();

        DepartmentRole::updateOrCreate(
            ['user_id' => $this->user->id],
            ['department_id' => $this->department, 'role' => $this->role]
        );

        $this->dispatchBrowserEvent('closeAllModals');

        $this->editing = false;

        $this->emit('departmentUpdated');

        supportFlash('Department Role updated!', 'success');
    }

    protected function getRules()
    {
        return [
            'department' => [
                'required',
                Rule::exists(Department::class, 'id')
            ],
            'role' => [
                'required',
                new Enum(DepartmentRolesEnum::class)
            ]
        ];
    }
}
