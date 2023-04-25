<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketStatusesEnum: string implements EnumContract
{
    use AsArray;
    case Pending = 'pending';
    // created, not assigned and already expired
    case Expired = 'expired';
    // Not completed yet, time passed,
    case InProgress = 'assigned';
    // Assigned, not completed yet, still on time
    case OnTime = 'on_time';
    // Completed on time
    case Late = 'late';
    // completed after timeframe

    public function class(): string
    {
        return match ($this) {
            self::Pending => '',
            self::Expired => 'badge badge-danger',
            self::InProgress => 'text-bold text-info',
            self::OnTime => 'badge badge-success',
            self::Late => 'badge badge-warning',
        };
    }
}
