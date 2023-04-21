<?php

namespace Dainsys\Support\Services;

use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\Department;

class DepartmentService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('departments_list', function () {
            return Department::orderBy('name')->pluck('name', 'id');
        });
    }

    public static function listWithReason()
    {
        return Cache::rememberForever('departments_list_with_reason', function () {
            return Department::orderBy('name')->whereHas('reasons')->pluck('name', 'id');
        });
    }

    public static function listWithRole()
    {
        return Cache::rememberForever('departments_list_with_role', function () {
            return Department::orderBy('name')->whereHas('role')->pluck('name', 'id');
        });
    }

    public static function count(): int
    {
        return Cache::rememberForever('departments_count', function () {
            return Department::count();
        });
    }
}
