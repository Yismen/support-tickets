<?php

namespace Dainsys\Support\Services\Ticket;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;

class ComplianceService extends BaseTicketService
{
    public function weeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float
    {
        return Cache::rememberForever(
            $this->cacheKey(__FUNCTION__, $week, $constraints),
            function () use ($week, $constraints) {
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
            }
        );
    }
}
