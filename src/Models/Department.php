<?php

namespace Dainsys\Support\Models;

use Dainsys\Support\Enums\DepartmentRolesEnum;
use Dainsys\Support\Models\Traits\HasManyReasons;
use Dainsys\Support\Models\Traits\HasManyTickets;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Dainsys\Support\Models\Traits\HasShortDescription;
use Dainsys\Support\Database\Factories\DepartmentFactory;

class Department extends AbstractModel
{
    use HasManyReasons;
    use HasManyTickets;
    use HasShortDescription;
    protected $fillable = ['name', 'ticket_prefix', 'description'];

    protected static function newFactory(): DepartmentFactory
    {
        return DepartmentFactory::new();
    }

    public function setTicketPrefixAttribute($prefix)
    {
        $upper = str($prefix)->upper();
        $prefix = str($upper)->endsWith('-') ? $upper : "{$upper}-";

        $this->attributes['ticket_prefix'] = $prefix;
    }

    public function roles(): HasMany
    {
        return $this->hasMany(DepartmentRole::class);
    }

    public function admins(): HasMany
    {
        return $this->roles()->where('role', DepartmentRolesEnum::Admin);
    }

    public function agents(): HasMany
    {
        return  $this->roles()->where('role', DepartmentRolesEnum::Agent);
    }
}
