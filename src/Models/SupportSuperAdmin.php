<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Database\Factories\SupportSuperAdminFactory;

class SupportSuperAdmin extends AbstractModel
{
    use BelongsToUser;

    protected $fillable = ['user_id'];

    protected static function newFactory(): SupportSuperAdminFactory
    {
        return SupportSuperAdminFactory::new();
    }
}
