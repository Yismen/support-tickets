<?php

namespace Dainsys\Support\Enums;

use Carbon\Carbon;

enum TicketPrioritiesEnum: int
{
    case Normal = 0;
    case Medium = 1;
    case High = 2;
    case Emergency = 4;

    public function expectedAt(): Carbon
    {
        return match ($this) {
            TicketPrioritiesEnum::Normal => self::ensureNotWeekend(now()->addDays(2)),
            TicketPrioritiesEnum::Medium => self::ensureNotWeekend(now()->addDay()),
            TicketPrioritiesEnum::High => self::ensureNotWeekend(now()->hours(4)),
            TicketPrioritiesEnum::Emergency => self::ensureNotWeekend(now()->addMinutes(30)),
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
