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
}
