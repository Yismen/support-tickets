<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Database\Factories\SuperAdminFactory;

class SuperAdmin extends AbstractModel
{
    use BelongsToUser;

    protected $fillable = ['user_id'];

    protected static function newFactory(): SuperAdminFactory
    {
        return SuperAdminFactory::new();
    }
}
