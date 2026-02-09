<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// TODO: WithTimestampsModel to Trait
class Server extends WithTimestampsModel
{
    use HasFactory, SoftDeletes;
    
    const RELATION_SERVER_TYPE = 'serverType';
    const RELATION_COUNTRY = 'country';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'server_type_id',
        'protocol_version',
        'ipv4',
        'country_id',
        'url',
        'main_token',
        'remote_token',
        'current_load',
        'avg_load',
        'port',
        'password',
        'encryption_method',
    ];

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function serverType() {
        return $this->hasOne(ServerType::class, 'id', 'server_type_id');
    }

    public function connectionsIn(): BelongsToMany
    {
        return $this->belongsToMany(ConnectionConfiguration::class, 'configuration_servers_in', 'server_in_id', 'connection_configuration_id')->withTimestamps();
    }

    public function connectionsOut(): BelongsToMany
    {
        return $this->belongsToMany(ConnectionConfiguration::class, 'configuration_servers_out', 'server_out_id', 'connection_configuration_id')->withTimestamps();
    }
}
