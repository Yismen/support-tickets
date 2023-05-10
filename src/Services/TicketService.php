<?php

namespace Dainsys\Support\Services;

use Carbon\Carbon;
use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Models\Department;
use Illuminate\Database\Eloquent\Builder;

class TicketService
{
    /**
     * Count for many weeks ago
     *
     * @param  integer $week Amount of weeks ago to query
     * @return void
     */
    public function countWeeksAgo(int $week, array $constraints = [], string $column = 'created_at')
    {
        $cache_key = join('-', [
            'weekly-tickets-count',
            $week,
            join('-', array_keys($constraints)),
            join('-', array_values($constraints)),
        ]);
        return Cache::rememberForever($cache_key, function () use ($week, $constraints) {
            return Ticket::query()
            ->ofWeeksAgo($week)
            ->when(count($constraints) > 0, function ($query) use ($constraints) {
                foreach ($constraints as $key => $value) {
                    if (!is_null($value)) {
                        $query->where($key, $value);
                    }
                }
            })
            ->count();
        });
    }

    /**
     * Count for many weeks ago
     *
     * @param  integer $week Amount of weeks ago to query
     * @return void
     */
    public function completionWeeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float
    {
        $cache_key = join('-', [
            'weekly-tickets-completion-rate',
            $week,
            join('-', array_keys($constraints)),
            join('-', array_values($constraints)),
        ]);

        return Cache::rememberForever($cache_key, function () use ($week, $constraints) {
            $total = Ticket::query()
            ->ofWeeksAgo($week)
            ->when(count($constraints) > 0, function ($query) use ($constraints) {
                foreach ($constraints as $key => $value) {
                    if (!is_null($value)) {
                        $query->where($key, $value);
                    }
                }
            })
            ->count();

            $completed = Ticket::query()
            ->ofWeeksAgo($week)
            ->ofWeeksAgo($week, 'completed_at')
            ->when(count($constraints) > 0, function ($query) use ($constraints) {
                foreach ($constraints as $key => $value) {
                    if (!is_null($value)) {
                        $query->where($key, $value);
                    }
                }
            })
            ->count();

            return $total > 0 ? $completed / $total : 1;
        });
    }

    /**
     * Count for many weeks ago
     *
     * @param  integer $week Amount of weeks ago to query
     * @return void
     */
    public function complianceWeeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float
    {
        $cache_key = join('-', [
            'weekly-tickets-compliance-rate',
            $week,
            join('-', array_keys($constraints)),
            join('-', array_values($constraints)),
        ]);

        return Cache::rememberForever($cache_key, function () use ($week, $constraints) {
            $total = Ticket::query()
                ->ofWeeksAgo($week, 'completed_at')
                ->when(count($constraints) > 0, function ($query) use ($constraints) {
                    foreach ($constraints as $key => $value) {
                        if (!is_null($value)) {
                            $query->where($key, $value);
                        }
                    }
                })
                ->count();

            $compliants = Ticket::query()
                ->ofWeeksAgo($week, 'completed_at')
                ->compliant()
                ->when(count($constraints) > 0, function ($query) use ($constraints) {
                    foreach ($constraints as $key => $value) {
                        if (!is_null($value)) {
                            $query->where($key, $value);
                        }
                    }
                })
                ->count();

            return $total > 0 ? $compliants / $total : 1;
        });
    }

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
