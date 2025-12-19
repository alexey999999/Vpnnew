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

    public function type() {
        return $this->hasOne(ServerType::class, 'id', 'server_type_id');
    }

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function serverType() {
        return $this->hasOne(ServerType::class, 'id', 'server_type_id');
    }
}
