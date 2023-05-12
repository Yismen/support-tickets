<?php

namespace Dainsys\Support\Services\Department;

use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\Department;

class DepartmentListService
{
    public static function withTicketsOnly()
    {
        $cache_key = join([
            str(self::class)->afterLast('\\')->kebab(),
            str(__FUNCTION__)->kebab(),
        ]);

        return Cache::rememberForever($cache_key, function () {
            return Department::query()
                ->orderBy('name')
                ->whereHas('tickets')
                ->get();
        });
    }
}
