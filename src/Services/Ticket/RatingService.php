<?php

namespace Dainsys\Support\Services\Ticket;

use Dainsys\Support\Models\Rating;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Dainsys\Support\Services\Ticket\Traits\HasCacheKey;

class RatingService
{
    use HasCacheKey;

    public function avg(int $week, array $constraints = [])
    {
        return Cache::rememberForever(
            $this->cacheKey(__FUNCTION__, $week, $constraints),
            function () use ($constraints, $week) {
                return $this->baseQuery($week, $constraints)->avg('score');
            }
        );
    }

    public function count(int $week, array $constraints = [])
    {
        return Cache::rememberForever(
            $this->cacheKey(__FUNCTION__, $week, $constraints),
            function () use ($constraints, $week) {
                return $this->baseQuery($week, $constraints)->count('score');
            }
        );
    }

    public function sum(int $week, array $constraints = [])
    {
        return Cache::rememberForever(
            $this->cacheKey(__FUNCTION__, $week, $constraints),
            function () use ($constraints, $week) {
                return $this->baseQuery($week, $constraints)->sum('score');
            }
        );
    }

    protected function baseQuery($week, $constraints): Builder
    {
        return Rating::query()
            ->withWhereHas('ticket', function ($query) use ($constraints, $week) {
                $query->ofWeeksAgo($week, 'created_at');

                foreach ($constraints as $field => $value) {
                    if (!is_null($value)) {
                        $query->where($field, $value);
                    }
                }
            });
    }
}
