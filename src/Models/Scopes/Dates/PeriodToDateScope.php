<?php

namespace Dainsys\Support\Models\Scopes\Dates;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use LaracraftTech\LaravelDateScopes\DateRange;

trait PeriodToDateScope
{
    protected function handle(
        Builder $query,
        Carbon $fromDate,
        $column = 'created_at',
        DateRange $dateRange = DateRange::INCLUSIVE
    ): Builder {
        $toDate = $dateRange->value === DateRange::INCLUSIVE ? now() : now()->subDay();

        return $query->where(function ($query) use ($column, $fromDate, $toDate) {
            $query->whereDate($column, '>=', $fromDate)
                ->whereDate($column, '<=', $toDate);
        });
    }

    public function scopeOfWeeksToDate(
        Builder $query,
        int $amount,
        $column = 'created_at',
        DateRange $dateRange = DateRange::INCLUSIVE
    ): Builder {
        return $this->handle(
            $query,
            now()->subWeeks($amount)->startOfWeek(),
            now()->endOfWeek(),
        );
    }
}
