<?php

namespace Dainsys\Support\Services;

use Dainsys\Support\Models\Subject;
use Illuminate\Support\Facades\Cache;

class SubjectService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('subjects_list', function () {
            return Subject::orderBy('name')->get()->pluck('name_with_priority', 'id');
        });
    }

    public static function listWithDeaprtment()
    {
        return Cache::rememberForever('subjects_list_with_department', function () {
            return Subject::orderBy('name')->whereHas('department')->get()->pluck('name_with_priority', 'id');
        });
    }

    public static function count(): int
    {
        return Cache::rememberForever('subjects_count', function () {
            return Subject::count();
        });
    }

    public static function listForDeaprtment($department)
    {
        return Cache::rememberForever("subjects_list_for_department_{$department}", function () use ($department) {
            return Subject::orderBy('name')->where('department_id', $department)->get()->pluck('name_with_priority', 'id');
        });
    }
}
