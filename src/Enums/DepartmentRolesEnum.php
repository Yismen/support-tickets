<?php

namespace Dainsys\Support\Enums;

enum DepartmentRolesEnum: string
{
    case Admin = 'admin';
    case Agent = 'agent';

    public static function asArray()
    {
        $return = [];

        foreach (self::cases() as $case) {
            $return[$case->value] = $case->name;
        }

        return $return;
    }
}
