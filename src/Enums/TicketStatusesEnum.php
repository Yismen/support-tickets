<?php

namespace Dainsys\Support\Enums;

enum TicketStatusesEnum: int
{
    case Pending = 0;
    case InProgress = 2;
    case OnHold = 3;
    case Completed = 4;
}
