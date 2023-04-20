<?php

namespace Dainsys\Support\Traits;

use Dainsys\Support\Models\Department;
use Dainsys\Support\Models\SuperAdmin;
use Dainsys\Support\Models\DepartmentRole;
use Dainsys\Support\Enums\DepartmentRolesEnum;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasSupportTickets
{
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
        return $this->departmentRole->role == DepartmentRolesEnum::Admin
            && $this->departmentRole->department_id === $department->id;
    }

    public function isDepartmentAgent(Department $department): bool
    {
        return $this->departmentRole->role == DepartmentRolesEnum::Agent
            && $this->departmentRole->department_id === $department->id;
    }

    protected function departmentRole(): HasOne
    {
        return $this->hasOne(DepartmentRole::class);
    }
}
