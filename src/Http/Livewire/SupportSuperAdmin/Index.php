<?php

namespace Dainsys\Support\Http\Livewire\SupportSuperAdmin;

// use App\Models\User;
use Livewire\Component;
use Dainsys\Support\Models\SupportSuperAdmin;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'superadminUpdated' => '$refresh',
    ];

    public $support_super_admins = [];

    public $selected ;

    public function mount()
    {
        $this->support_super_admins = SupportSuperAdmin::pluck('user_id')->values()->toArray();
    }

    public function render()
    {
        $this->authorize('viewAny', new SupportSuperAdmin());

        return view('support::livewire.support_super_admin.index', [
            'users' => \App\Models\User::orderBy('name')->with('supportSuperAdmin')->get()
        ])
        ->layout('support::layouts.app');
    }

    public function updating($support_super_admins, $value)
    {
        $this->selected = $this->findCurrentUser($value);
        $user = \App\Models\User::findOrFail($this->selected)->load('supportSuperAdmin');

        if (auth()->user()->id === $user->id) {
            return supportFlash('You can\' update your own user!', 'error');
        }
        if ($user->isSupportSuperAdmin()) {
            $user->supportSuperAdmin()->delete();

            return supportFlash("User {$user->name} is not a support super admin user anymore!", 'warning');
        }

        $user->SupportSuperAdmin()->create();

        return supportFlash("Added {$user->name} as a support super admin user!", 'success');
    }

    protected function findCurrentUser(array $value): int
    {
        try {
            $diff = array_diff($this->support_super_admins->toArray(), $value);

            $diff = empty($diff)
                ? array_diff($value, $this->support_super_admins->toArray())
                : $diff;
        } catch (\Throwable $th) {
            $diff = array_diff($this->support_super_admins, $value);

            $diff = empty($diff)
                ? array_diff($value, $this->support_super_admins)
                : $diff;
        }

        return \Illuminate\Support\Arr::first($diff);
    }
}
