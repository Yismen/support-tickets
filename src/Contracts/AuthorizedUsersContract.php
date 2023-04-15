<?php

namespace Dainsys\Support\Contracts;

interface AuthorizedUsersContract
{
    public function has(string $email): bool;
}
