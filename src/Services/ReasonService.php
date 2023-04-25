<?php

namespace Dainsys\Support\Services;

use Dainsys\Support\Models\Reason;
use Illuminate\Support\Facades\Cache;

class ReasonService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('reasons_list', function () {
            return Reason::orderBy('name')->pluck('name', 'id');
        });
    }

    public static function listWithDeaprtment()
    {
        return Cache::rememberForever('reasons_list_with_department', function () {
            return Reason::orderBy('name')->whereHas('department')->pluck('name', 'id');
        });
    }

    public static function count(): int
    {
        return Cache::rememberForever('reasons_count', function () {
            return Reason::count();
        });
    }

    public static function listForDeaprtment($department)
    {
        return Cache::rememberForever("reasons_list_for_department_{$department}", function () use ($department) {
            return Reason::orderBy('name')->where('department_id', $department)->pluck('name', 'id');
        });
    }
}
