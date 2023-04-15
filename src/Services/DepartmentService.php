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

    public static function count(): int
    {
        return Cache::rememberForever('departments_count', function () {
            return Department::count();
        });
    }
}
