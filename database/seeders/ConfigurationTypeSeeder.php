<?php

namespace Database\Seeders;

use App\Models\ConfigurationType;
use Illuminate\Database\Seeder;

class ConfigurationTypeSeeder extends Seeder
{
    public function run()
    {
        $configurationTypeArr = ['ShadowSocks', 'DoubleVPN'];

        foreach ($configurationTypeArr as $configurationType)
        {
            (new ConfigurationType)->fill([
                'name' => $configurationType
            ])->save();
        }
    }
}
