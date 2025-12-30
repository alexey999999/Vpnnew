<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'server_type_id',
        'ipv4',
        'country_id',
        'url',
        'name',
        'main_token',
        'remote_token',
        'current_load',
        'avg_load',
        'port',
        'password',
        'encryption_method',
    ];
    
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
        ];
    }

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function serverType() {
        return $this->hasOne(ServerType::class, 'id', 'server_type_id');
    }
}
