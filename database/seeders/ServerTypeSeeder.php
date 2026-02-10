<?php

namespace Database\Seeders;

use App\Models\ServerType;
use Illuminate\Database\Seeder;

class ServerTypeSeeder extends Seeder
{
    public function run()
    {
        $serverTypeArr = [
            ServerType::SS,
            ServerType::VPN_IO,
            ServerType::VPN_IN,
            ServerType::VPN_OUT,
        ];

        foreach ($serverTypeArr as $serverType)
        {
            (new ServerType)->fill([
                'name' => $serverType
            ])->save();
        }
    }
}
