<?php

namespace Dainsys\Support\Services\Ticket;

use Dainsys\Support\Models\Ticket;
use Illuminate\Support\Facades\Cache;
use Dainsys\Support\Services\Ticket\Traits\HasCacheKey;

class OldestTicketService
{
    use HasCacheKey;

    public function getOldestTicket(): Ticket
    {
        return Cache::rememberForever(
            $this->cacheKey(__METHOD__, 0, []),
            function () {
                return Ticket::query()
                    ->oldest()
                    ->first()
                    ??
                    new Ticket();
            }
        );
    }

    public function weeksSinceOldestTicket(int $limit): int
    {
        $diff = now()->diffInWeeks(
            $this->getOldestTicket()->created_at
        );

        return $diff < $limit ? $diff : $limit;
    }
}
