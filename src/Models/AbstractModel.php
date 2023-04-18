<?php

namespace Dainsys\Support\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbstractModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected static function booted()
    {
        parent::booted();

        static::saved(function ($user) {
            Cache::flush();
        });
    }

    public function getTable(): string
    {
        return supportTableName(str(get_class($this))->afterLast('\\')->plural()->snake()->lower());
    }
}
