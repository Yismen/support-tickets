<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketPrioritiesEnum: int implements EnumContract
{
    use AsArray;
    case Normal = 1;
    case Medium = 2;
    case High = 3;
    case Emergency = 4;

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
