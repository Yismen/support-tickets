<?php

namespace Dainsys\Support\Enums;

use Carbon\Carbon;

enum TicketPriority: int
{
    case Normal = 0;
    case Medium = 1;
    case High = 2;
    case Emergency = 4;

    public function expectedAt(): Carbon
    {
        return match ($this) {
            TicketPriority::Normal => self::ensureNotWeekend(now()->addDays(2)),
            TicketPriority::Medium => self::ensureNotWeekend(now()->addDay()),
            TicketPriority::High => self::ensureNotWeekend(now()->hours(4)),
            TicketPriority::Emergency => self::ensureNotWeekend(now()->addMinutes(30)),
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
