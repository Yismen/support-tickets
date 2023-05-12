<?php

namespace Dainsys\Support\Services\Ticket;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;

class CompletionService extends BaseTicketService
{
    public function weeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float
    {
        // dd('Asdfdf');
        return Cache::rememberForever(
            $this->cacheKey(__METHOD__, $week, $constraints),
            function () use ($week, $constraints) {
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
            }
        );
    }
}
