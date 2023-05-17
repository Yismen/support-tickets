<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketRatingsEnum: int implements EnumContract
{
    use AsArray;
    case Unacceptable = 1;
    case NeedsImprovement = 2;
    case MeetsExpectations = 3;
    case ExceedsExpectations = 4;
    case Outstanding = 5;
    public function class(): string
    {
        return match ($this) {
            self::Unacceptable => 'text-danger text-bold',
            self::NeedsImprovement => 'text-fuchsia text-bold',
            self::MeetsExpectations => 'text-dark text-bold',
            self::ExceedsExpectations => 'text-info text-bold',
            self::Outstanding => 'text-success text-bold',
        };
    }
}
