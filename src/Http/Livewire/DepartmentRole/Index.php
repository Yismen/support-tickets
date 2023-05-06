<?php

namespace Dainsys\Support\Http\Livewire\DepartmentRole;

// use App\Models\User;
use Livewire\Component;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'departmentRoleUpdated' => '$refresh',
    ];

    public $department_roles = [];

    public $selected ;

    public function mount()
    {
        $this->department_roles = DepartmentRole::pluck('user_id')->values()->toArray();
    }

    public function render()
    {
        $this->authorize('viewAny', new DepartmentRole());

        return view('support::livewire.department_role.index', [
            'users' => \App\Models\User::orderBy('name')->with('supportSuperAdmin')->get()
        ])
        ->layout('support::layouts.app');
    }

    public function updating($department_roles, $value)
    {
        $this->selected = $this->findCurrentUser($value);
        $user = \App\Models\User::findOrFail($this->selected)->load('supportSuperAdmin');

        if (auth()->user()->id === $user->id) {
            return supportFlash('You can\' update your own user!', 'error');
        }
        if ($user->isDepartmentRole()) {
            $user->supportSuperAdmin()->delete();

            return supportFlash("User {$user->name} is not a support super admin user anymore!", 'warning');
        }

        $user->DepartmentRole()->create();

        return supportFlash("Added {$user->name} as a support super admin user!", 'success');
    }

    protected function findCurrentUser(array $value): int
    {
        try {
            $diff = array_diff($this->department_roles->toArray(), $value);

            $diff = empty($diff)
                ? array_diff($value, $this->department_roles->toArray())
                : $diff;
        } catch (\Throwable $th) {
            $diff = array_diff($this->department_roles, $value);

            $diff = empty($diff)
                ? array_diff($value, $this->department_roles)
                : $diff;
        }

        return \Illuminate\Support\Arr::first($diff);
    }
}
