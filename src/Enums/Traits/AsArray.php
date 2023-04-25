<?php

namespace Dainsys\Support\Enums\Traits;

trait AsArray
{
    public static function asArray(): array
    {
        $return = [];

        foreach (self::cases() as $case) {
            $return[$case->value] = $case->name;
        }

        return $return;
    }
}
