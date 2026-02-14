<?php

namespace App\Models;

use App\Trait\WithTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tariff extends Model
{
    use HasFactory, SoftDeletes, WithTimestamps;

    const RELATION_CONFIGURATIONS = 'configurations';

    protected $fillable = ['name'];

    public function configurations(): BelongsToMany
    {
        return $this->belongsToMany(ConnectionConfiguration::class, 'tariff_configurations', 'tariff_id', 'configuration_id')->withTimestamps();
    }
}
