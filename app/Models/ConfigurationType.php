<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ConfigurationType extends Model
{
    use HasFactory;

    const SHADOW_SOCKS = 'ShadowSocks';
    const DOUBLE_VPN = 'DoubleVPN';
}
