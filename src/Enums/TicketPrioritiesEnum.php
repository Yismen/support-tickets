<?php

namespace Dainsys\Support\Enums;

use Carbon\Carbon;
use Dainsys\Support\Enums\Traits\AsArray;

enum TicketPrioritiesEnum: int implements EnumContract
{
    use AsArray;
    case Normal = 0;
    case Medium = 1;
    case High = 2;
    case Emergency = 3;

    public function expectedAt(): Carbon
    {
        return match ($this) {
            self::Normal => self::ensureNotWeekend(now()->addDays(2)),
            self::Medium => self::ensureNotWeekend(now()->addDay()),
            self::High => self::ensureNotWeekend(now()->hours(4)),
            self::Emergency => self::ensureNotWeekend(now()->addMinutes(30)),
        };
    }

    public function class(): string
    {
        return match ($this) {
            self::Normal => 'text-black',
            self::Medium => 'badge badge-info',
            self::High => 'badge badge-warning',
            self::Emergency => 'badge badge-danger',
        };
    }

    protected static function ensureNotWeekend(Carbon $date): Carbon
    {
        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }
}
