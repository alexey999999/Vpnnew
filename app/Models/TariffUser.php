<?php

namespace App\Models;

use App\Trait\WithTimestamps;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TariffUser extends Pivot
{
    use WithTimestamps;
    
    protected $table = 'tariff_users';

    public function getIsActiveAttribute()
    {
        return $this->active_to > now();
    }
}
