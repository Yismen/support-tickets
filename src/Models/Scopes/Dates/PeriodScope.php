<?php

namespace Dainsys\Support\Models\Scopes\Dates;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/** @method Builder ofDaysAgo() */
/** @method Builder ofWeeksAgo() */
/** @method Builder ofMonthssAgo() */
/** @method Builder ofYearssAgo() */
trait PeriodScope
{
    public function scopeOfDaysAgo(Builder $query, int $amount, string $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subDays($amount)->startOfDay(), now()->subDays($amount)->endOfDay(), $column);
    }

    public function scopeOfWeeksAgo(Builder $query, int $amount, string $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subWeeks($amount)->startOfWeek(), now()->subWeeks($amount)->endOfWeek(), $column);
    }

    public function scopeOfMonthsAgo(Builder $query, int $amount, string $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subMonths($amount)->startOfMonth(), now()->subMonths($amount)->endOfMonth(), $column);
    }

    public function scopeOfYearsAgo(Builder $query, int $amount, string $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subYears($amount)->startOfYear(), now()->subYears($amount)->endOfYear(), $column);
    }

    protected function handle(Builder $query, Carbon $fromDate, Carbon $toDate, string $column = 'created_at'): Builder
    {
        return $query->where(function ($query) use ($column, $fromDate, $toDate) {
            $query->whereDate($column, '>=', $fromDate)
                ->whereDate($column, '<=', $toDate);
        });
    }
}
