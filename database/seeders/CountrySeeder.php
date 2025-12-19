<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Symfony\Component\Intl\Countries;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = Countries::getNames();
        foreach ($countries as $alpha2Code => $country)
        {
            (new Country)->fill([
                'code' => $alpha2Code,
                'name' => $country
            ])->save();
        }
    }
}
