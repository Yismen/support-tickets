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

    public function period()
    {
        return match ($this) {
            self::Normal => '48 ' . __('support::messages.hours'),
            self::Medium => '24 ' . __('support::messages.hours'),
            self::High => '4 ' . __('support::messages.hours'),
            self::Emergency => '30 ' . __('support::messages.minutes') ,
        };
    }
}
