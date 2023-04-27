<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketPrioritiesEnum: int implements EnumContract
{
    use AsArray;
    case Normal = 0;
    case Medium = 1;
    case High = 2;
    case Emergency = 3;

    public function class(): string
    {
        return match ($this) {
            self::Normal => 'text-black',
            self::Medium => 'badge badge-info',
            self::High => 'badge badge-warning',
            self::Emergency => 'badge badge-danger',
        };
    }
}
