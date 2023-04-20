<?php

namespace Dainsys\Support\Http\Livewire\SuperAdmin;

// use App\Models\User;
use Livewire\Component;
use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'superadminUpdated' => '$refresh',
    ];

    public $super_admins = [];

    public $selected ;

    public function mount()
    {
        $this->super_admins = SuperAdmin::pluck('user_id')->values()->toArray();
    }

    public function render()
    {
        $this->authorize('viewAny', new SuperAdmin());

        return view('support::livewire.super_admin.index', [
            'users' => \App\Models\User::orderBy('name')->with('superAdmin')->get()
        ])
        ->layout('support::layouts.app');
    }

    public function updating($super_admins, $value)
    {
        $this->selected = $this->findCurrentUser($value);
        $user = \App\Models\User::findOrFail($this->selected)->load('superAdmin');

        // dd($value, $this->selected, $this->super_admins, auth()->user()->id, $user->id);
        if (auth()->user()->id === $user->id) {
            return flasher('You can\' update your own user!', 'error');
        }
        if ($user->isSuperAdmin()) {
            $user->superAdmin()->delete();

            return flasher("User {$user->name} is not a super admin user anymore!", 'warning');
        }

        $user->SuperAdmin()->create();

        return flasher("Added {$user->name} as a super admin user!", 'success');
    }

    protected function findCurrentUser(array $value): int
    {
        try {
            $diff = array_diff($this->super_admins->toArray(), $value);

            $diff = empty($diff)
                ? array_diff($value, $this->super_admins->toArray())
                : $diff;
        } catch (\Throwable $th) {
            $diff = array_diff($this->super_admins, $value);

            $diff = empty($diff)
                ? array_diff($value, $this->super_admins)
                : $diff;
        }

        return \Illuminate\Support\Arr::first($diff);
    }
}
