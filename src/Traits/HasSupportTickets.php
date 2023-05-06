<?php

namespace Dainsys\Support\Traits;

use Dainsys\Support\Models\Ticket;
use Dainsys\Support\Models\Department;
use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Notifications\Notifiable;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasSupportTickets
{
    use Notifiable;

    public function superAdmin(): HasOne
    {
        return $this->hasOne(SuperAdmin::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->superAdmin()->exists();
    }

    public function isDepartmentAdmin(Department $department): bool
    {
        return $this->hasDepartmentRole($department, DepartmentRolesEnum::Admin);
    }

    public function isDepartmentAgent(Department $department): bool
    {
        return $this->hasDepartmentRole($department, DepartmentRolesEnum::Agent);
    }

    public function hasDepartmentRole(Department $department, DepartmentRolesEnum $role): bool
    {
        return $this->departmentRole->role === $role && $department->id === $this->departmentRole->department_id;
    }

    public function hasAnyDepartmentRole(): bool
    {
        return $this->departmentRole()->exists();
    }

    public function departmentRole(): HasOne
    {
        return $this->hasOne(DepartmentRole::class);
    }

    public function department(): BelongsTo
    {
        // dd(new Department(['name' => null]));
        return $this->departmentRole?->department();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }
}
