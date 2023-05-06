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

    protected static function booted()
    {
        parent::booted();

        static::saved(function ($model) {
            $prefix = $model->ticket_prefix;
            $upper = str($prefix)->upper();
            $parsed = str($upper)->endsWith('-') ? $upper : "{$upper}-";

            $model->updateQuietly(['ticket_prefix' => $parsed]);
        });
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

    public function getTicketsCompletedAttribute(): int
    {
        return $this->tickets()->completed()->count();
    }

    public function getTicketsIncompletedAttribute(): int
    {
        return $this->tickets()->incompleted()->count();
    }

    public function getTicketsCompliantsAttribute(): int
    {
        return $this->tickets()->compliant()->count();
    }

    public function getCompletionRateAttribute()
    {
        $total_tickets = $this->tickets()->count();

        return $total_tickets > 0
            ? $this->tickets_completed / $total_tickets
            : 0;
    }

    public function getComplianceRateAttribute()
    {
        $total_tickets = $this->tickets()->completed()->count();

        return $total_tickets > 0
            ? $this->tickets_compliants / $total_tickets
            : 0;
    }
}
