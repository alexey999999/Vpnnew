<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithTimestampsModel extends Model
{    
    protected const DATE_TIME_FORMAT = 'H:i:s d.m.Y';

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:' . self::DATE_TIME_FORMAT,
            'updated_at' => 'datetime:' . self::DATE_TIME_FORMAT,
            'deleted_at' => 'datetime:' . self::DATE_TIME_FORMAT,
        ];
    }
}
