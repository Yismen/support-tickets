<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Models\Traits\BelongsToUser;
use Dainsys\Support\Models\Traits\BelongsToDepartment;
use Dainsys\Support\Database\Factories\DepartmentRoleFactory;

class DepartmentRole extends AbstractModel
{
    use BelongsToUser;
    use BelongsToDepartment;

    protected $fillable = ['user_id', 'department_id', 'role'];

    protected $casts = [
        'role' => DepartmentRolesEnum::class
    ];

    protected static function newFactory(): DepartmentRoleFactory
    {
        return DepartmentRoleFactory::new();
    }
}
