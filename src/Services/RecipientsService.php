<?php

namespace Dainsys\Support\Services;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Eloquent\Collection;
use Dainsys\Support\Models\SupportSuperAdmin;
use Dainsys\Support\Enums\DepartmentRolesEnum;

class RecipientsService
{
    protected Ticket $ticket;
    protected Collection $recipients;

    public function __construct()
    {
        $this->recipients = new Collection();
    }

    public function ofTicket($ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function recipients(): Collection
    {
        $recipients = $this->recipients
            ->filter(function ($user) {
                return $user?->email;
            });

        return config('support.email.include_current_user', false)
            ? $recipients
            : $recipients->filter(function ($user) {
                return $user->id !== auth()->user()?->id;
            });
    }

    public function owner(): self
    {
        $this->ticket->load('owner');

        $this->recipients->push($this->ticket->owner);

        return $this;
    }

    public function superAdmins(): self
    {
        $super_admins = SupportSuperAdmin::get()->map->user;

        if ($super_admins->count()) {
            $this->recipients = $this->recipients->merge($super_admins);
        }

        return $this;
    }

    public function allDepartmentAdmins(): self
    {
        $admins = DepartmentRole::query()
            ->where('role', DepartmentRolesEnum::Admin)
            ->where('department_id', $this->ticket->department_id)
            ->with('user')
            ->get()->map->user;

        if ($admins->count()) {
            $this->recipients = $this->recipients->merge($admins);
        }

        return $this;
    }

    public function allDepartmentAgents(): self
    {
        $agents = DepartmentRole::query()
            ->with('user')
            ->where('role', DepartmentRolesEnum::Agent)
            ->where('department_id', $this->ticket->department_id)->get()->map->user;

        if ($agents->count()) {
            $this->recipients = $this->recipients->merge($agents);
        }

        return $this;
    }

    public function agent(): self
    {
        $this->ticket->load('agent');

        $this->recipients = $this->recipients->push($this->ticket->agent);

        return $this;
    }
}
