<?php

namespace Dainsys\Support\Services\Ticket\Traits;

trait HasCacheKey
{
    protected function cacheKey(string $method, int $week, array $constraints): string
    {
        $chain_string = '-';

        return join($chain_string, [
            str(get_class($this))->afterLast('\\')->snake(),
            $week,
            join($chain_string, array_keys($constraints)),
            join($chain_string, array_values($constraints)),
        ]);
    }
}
