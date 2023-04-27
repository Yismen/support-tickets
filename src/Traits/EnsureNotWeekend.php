<?php

namespace Dainsys\Support\Traits;

use Carbon\Carbon;

trait EnsureNotWeekend
{
    protected static function ensureNotWeekend(Carbon $date): Carbon
    {
        while ($date->isWeekend()) {
            $date->addDay();
        }

        return $date;
    }
}
