<?php

namespace Dainsys\Support\Services\Ticket;

abstract class BaseTicketService
{
    abstract public function weeksAgo(int $week, array $constraints = [], string $column = 'created_at'): float;

    protected function cacheKey(string $method, int $week, array $constraints): string
    {
        $chain_string = '-';

        return join($chain_string, [
            str(get_class($this))->snake(),
            $week,
            join($chain_string, array_keys($constraints)),
            join($chain_string, array_values($constraints)),
        ]);
    }
}
