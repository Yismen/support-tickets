<?php

namespace Dainsys\Support\Services\Department;

use Dainsys\Support\Models\DepartmentRole;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public static function members($department_id = null): Collection
    {
        return DepartmentRole::where('department_id', $department_id)->get();
    }
}
