<?php

namespace Dainsys\Support\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\SupportSuperAdmin;

class SupportSuperAdminService implements ServicesContract
{
    public static function list()
    {
        return Cache::rememberForever('SupportSuperAdmins_list', function () {
            return User::with('support_super_admin')->get();
        });
    }

    public static function count(): int
    {
        return Cache::rememberForever('SupportSuperAdmins_count', function () {
            return SupportSuperAdmin::count();
        });
    }
}
