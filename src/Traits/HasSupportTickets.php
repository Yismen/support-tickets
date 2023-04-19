<?php

namespace Dainsys\Support\Traits;

use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasSupportTickets
{
    public function superAdmin(): HasOne
    {
        return $this->hasOne(SuperAdmin::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->superAdmin()->exists();
    }
}
