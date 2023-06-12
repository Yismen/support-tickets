<?php

namespace Dainsys\Support\Models\Traits;

use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyDepartmentRoles
{
    public function departmentRoles(): HasMany
    {
        return $this->hasMany(DepartmentRole::class);
    }
}
