<?php

namespace Dainsys\Support\Services\Ticket;

use Dainsys\Support\Services\Ticket\Traits\HasCacheKey;

abstract class BaseTicketService
{
    use HasCacheKey;

    abstract public function weeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float;
}
