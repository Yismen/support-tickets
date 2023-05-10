<?php

namespace Dainsys\Support\Models\Scopes\Dates;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait PeriodScope
{
    /**
     * Records for many weeks ago
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  integer                               $amount The amount of weeks to go back
     * @param  string                                $column
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfWeeksAgo(
        Builder $query,
        int $amount,
        string $column = 'created_at'
    ): Builder {
        return $this->handle(
            $query,
            now()->subWeeks($amount)->startOfWeek(),
            now()->subWeeks($amount)->endOfWeek(),
            $column
        );
    }

    protected function handle(Builder $query, Carbon $fromDate, Carbon $toDate, string $column = 'created_at'): Builder
    {
        return $query->where(function ($query) use ($column, $fromDate, $toDate) {
            $query->whereDate($column, '>=', $fromDate)
                ->whereDate($column, '<=', $toDate);
        });
    }
}
