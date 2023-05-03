<?php

namespace Dainsys\Support\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\SuperAdmin;

class SuperAdminService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('SuperAdmins_list', function () {
            return User::with('super_admin')->get();
        });
    }

    public static function count(): int
    {
        return Cache::rememberForever('SuperAdmins_count', function () {
            return SuperAdmin::count();
        });
    }
}
