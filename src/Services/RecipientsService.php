<?php

namespace Dainsys\Support\Services;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class RecipientsService
{
    protected Ticket $ticket;
    protected Collection $recipients;

    public function __construct()
    {
        $this->recipients = new Collection();
    }

    public function ofTicket(Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function get(): Collection
    {
        $recipients = $this->recipients
            ->filter(function ($user) {
                if (is_subclass_of($user, MustVerifyEmail::class) && $user->email_verified_at === null) {
                    return false;
                }
                return $user?->email;
            });

        return config('support.email.include_current_user', false)
            ? $recipients
            : $recipients->filter(function ($user) {
                return $user->id !== auth()->user()?->id;
            });
    }

    public function superAdmins(): self
    {
        $super_admins = SupportSuperAdmin::query()
            ->with('user')
            ->get() 
            ->map->user->filter(function ($user) {
                return $user?->email;
            });

        if ($super_admins->count()) {
            $this->recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            })
            ->merge($super_admins);
        }

        return $this;
    }

    public function allDepartmentAdmins(): self
    {
        $admins = DepartmentRole::query()
            ->where('role', DepartmentRolesEnum::Admin)
            ->with('user')
            ->get() 
            ->map->user->filter(function ($user) {
                return $user?->email;
            });

        if ($admins->count()) {
            $this->recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            })
            ->merge($admins);
        }

        return $this;
    }

    public function owner($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;
        $ticket->load('owner');

        $this->recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            })
            ->push($ticket->owner);

        return $this;
    }

    public function agent($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;
        $ticket->load('agent');

        $this->recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            })
            ->push($ticket->agent);

        return $this;
    }

    public function departmentAdmins($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;

        $admins = DepartmentRole::query()
            ->where('role', DepartmentRolesEnum::Admin)
            ->where('department_id', $ticket->department_id)
            ->with('user')
            ->get() 
            ->map->user->filter(function ($user) {
                return $user?->email;
            });

        if ($admins->count()) {
            $this->recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            })
            ->merge($admins);
        }

        return $this;
    }

    public function departmentAgents($ticket = null): self
    {
        $ticket = $ticket ?: $this->ticket;

        $agents = DepartmentRole::query()
            ->with('user')
            ->where('role', DepartmentRolesEnum::Agent)
            ->where('department_id', $ticket->department_id)->get() 
            ->map->user->filter(function ($user) {
                return $user?->email;
            });

        if ($agents->count()) {
            $this->recipients = $this->recipients
                ->filter(function ($user) {
                    return $user?->email;
                })
                ->merge($agents);
        }

        return $this;
    }
}
