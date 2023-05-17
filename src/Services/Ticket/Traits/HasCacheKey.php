<?php

namespace Dainsys\Support\Services\Ticket\Traits;

trait HasCacheKey
{
    protected function cacheKey(string $method, int $week, array $constraints): string
    {
        $chain_string = '_';

        return join($chain_string, [
            str(get_class($this))->replace('\\', ' ')->snake(),
            $method,
            $week,
            join($chain_string, array_keys($constraints)),
            join($chain_string, array_values($constraints)),
        ]);
    }
}
