<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum TicketProgressesEnum: string implements EnumContract
{
    use AsArray;
    case Pending = 'pending';
    // created, not assigned and already expired
    case InProgress = 'assigned';
    // Assigned, not completed yet, still on time

    case Completed = 'completed';

    public function class(): string
    {
        return match ($this) {
            self::Pending => '',
            self::InProgress => 'badge badge-warning',
            self::Completed => 'badge badge-info',
        };
    }
}
