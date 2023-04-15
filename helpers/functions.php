<?php

if (function_exists('supportTableName') === false) {
    function supportTableName(string $name)
    {
        return config('support.db_prefix') . $name;
    }
}

if (function_exists('str') === false) {
    function str(string $string)
    {
        return \Illuminate\Support\Str::of($string);
    }
}
