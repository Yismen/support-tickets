<?php

namespace Dainsys\Support\Services;

use Carbon\Carbon;
use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class TicketService
{
    public static function byDepartment(Department|null|int $department = null): Builder
    {
        $department = $department instanceof Department
            ? $department
            : self::resolveDepartment($department);

        return Ticket::query()
            ->when(
                $department->id ?? null,
                function (Builder $query) use ($department) {
                    $query->where('department_id', $department->id);
                }
            );
    }

    public static function completionRate(Department|null|int $department = null): float
    {
        return Cache::remember(
            self::cacheKey($department, __FUNCTION__),
            self::cacheTtl(),
            function () use ($department) {
                $total = self::byDepartment($department)->count();
                $completed = self::byDepartment($department)->completed()->count();

                return $total
                    ? $completed / $total
                    : 0;
            }
        );
    }

    public static function complianceRate(Department|null|int $department = null): float
    {
        return Cache::remember(
            self::cacheKey($department, __FUNCTION__),
            self::cacheTtl(),
            function () use ($department) {
                $total = self::byDepartment($department)->count();
                $compliant = self::byDepartment($department)->compliant()->count();

                return $total
                    ? $compliant / $total
                    : 0;
            }
        );
    }

    protected static function cacheKey($department, $method): string
    {
        $method = str($method)->snake();
        $department = $department->id ?? $department;

        return "ticket_service_{$method}_{$department}";
    }

    public static function cacheTtl(): Carbon
    {
        return now()->addMinutes(15);
    }

    protected static function resolveDepartment($department)
    {
        return Cache::rememberForever('department_ticket_service_' . $department, function () use ($department) {
            return Department::find($department);
        });
    }
}