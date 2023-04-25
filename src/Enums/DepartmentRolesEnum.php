<?php

namespace Dainsys\Support\Enums;

use Dainsys\Support\Enums\Traits\AsArray;

enum DepartmentRolesEnum: string implements EnumContract
{
    use AsArray;
    case Admin = 'admin';
    case Agent = 'agent';
}
