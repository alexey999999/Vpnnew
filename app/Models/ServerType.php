<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ServerType extends Model
{
    use HasFactory;
    
    const VPN_IO = 'vpn_io';
    const VPN_IN = 'vpn_in';
    const VPN_OUT = 'vpn_out';
    const SS = 'ss';
}
