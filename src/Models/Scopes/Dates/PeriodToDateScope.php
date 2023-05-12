<?php

namespace Dainsys\Support\Models\Scopes\Dates;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait PeriodToDateScope
{
    protected function handle(
        Builder $query,
        Carbon $fromDate,
        string $column
    ): Builder {
        return $query->where(function ($query) use ($column, $fromDate) {
            $query->whereDate($column, '>=', $fromDate)
                ->whereDate($column, '<=', now());
        });
    }

    public function scopeOfDaysToDate(Builder $query, int $amount, $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subDays($amount)->startOfDay(), $column);
    }

    public function scopeOfWeeksToDate(Builder $query, int $amount, $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subWeeks($amount)->startOfWeek(), $column);
    }

    public function scopeOfMonthsToDate(Builder $query, int $amount, $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subMonths($amount)->startOfMonth(), $column);
    }

    public function scopeOfYearsToDate(Builder $query, int $amount, $column = 'created_at'): Builder
    {
        return $this->handle($query, now()->subYears($amount)->startOfYear(), $column);
    }
}
