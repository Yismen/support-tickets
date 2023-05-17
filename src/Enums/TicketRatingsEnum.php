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
            self::Unacceptable => 'bg-danger',
            self::NeedsImprovement => 'bg-danger disabled',
            self::MeetsExpectations => 'bg-secondary',
            self::ExceedsExpectations => 'bg-success',
            self::Outstanding => 'bg-success disabled',
        };
    }
}
