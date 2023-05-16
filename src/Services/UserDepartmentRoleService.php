<?php

namespace Dainsys\Support\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserDepartmentRoleService
{
    public static function list($department)
    {
        return Cache::rememberForever('user_department_role_service_' . $department?->id, function () use ($department) {
            return User::withWhereHas('departmentRole', function ($query) use ($department) {
                $query->when($department, function ($query) use ($department) {
                    $query->where('department_id', $department->id);
                });
            })->pluck('name', 'id');
        });
    }
}
