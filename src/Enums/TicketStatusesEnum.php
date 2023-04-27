<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketStatusesEnum: int implements EnumContract
{
    use AsArray;
    case Pending = 1;
    case PendingExpired = 2;
    case InProgress = 3;
    case InProgressExpired = 4;
    case Completed = 5;
    case CompletedExpired = 6;
    public function class(): string
    {
        return match ($this) {
            self::Pending => '',
            self::PendingExpired => 'text-bold text-warning',
            self::InProgress => 'badge badge-info',
            self::InProgressExpired => 'badge badge-warning',
            self::Completed => 'badge badge-success',
            self::CompletedExpired => 'badge badge-danger',
        };
    }
}
