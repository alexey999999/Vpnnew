<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConnectionConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'configuration_type_id'];

    /**
     * Scope a query to only include connection configurations which not deleted.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('deleted_at', null);
    }

    public function configurationType(): BelongsTo
    {
        return $this->belongsTo(ConfigurationType::class);
    }

    public function serversIn(): BelongsToMany
    {
        return $this->belongsToMany(Server::class, 'configuration_servers_in', 'connection_configuration_id', 'server_in_id')->withTimestamps();;
    }

    public function serversOut(): BelongsToMany
    {
        return $this->belongsToMany(Server::class, 'configuration_servers_out', 'connection_configuration_id', 'server_out_id')->withTimestamps();;
    }
}
