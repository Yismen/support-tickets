<?php

namespace Dainsys\Support\Contracts;

interface InstanceFromNameContract
{
    public function __construct(string $namespace);

    public function get($class);
}
