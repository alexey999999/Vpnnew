<?php

namespace Database\Seeders;

use App\Models\ServerType;
use Illuminate\Database\Seeder;

class ServerTypeSeeder extends Seeder
{
    public function run()
    {
        $serverTypeArr = ['vpn_io', 'vpn_in', 'vpn_out', 'ss'];

        foreach ($serverTypeArr as $serverType)
        {
            (new ServerType)->fill([
                'name' => $serverType
            ])->save();
        }
    }
}
