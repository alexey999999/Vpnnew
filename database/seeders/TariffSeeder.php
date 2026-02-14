<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\ConnectionConfiguration;
use App\Models\Tariff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffSeeder extends Seeder
{
    public function run()
    {
        $numRows = config('app.tariffs.seeder.num_rows');

        for ($i = 0; $i < $numRows; $i++) {
            $configurationsCount = rand(1, config('app.tariffs.seeder.max_configurations'));

            /** @var ConnectionConfiguration $connectionConfiguration */
            $connectionConfigurations = ConnectionConfiguration::inRandomOrder()
                ->limit($configurationsCount)
                ->get();

            try {
                DB::transaction(function () use ($connectionConfigurations) {
                    /** @var Tariff $tariff */
                    $tariff = (new Tariff())->create([
                        'name' => 'tariff-' . fake()->slug(1),
                    ]);

                    $tariff->configurations()->attach($connectionConfigurations);

                    // If all operations succeed, the transaction is committed automatically
                });
            } catch (\Exception $e) {
                // If an exception occurred in the closure, all changes are rolled back
                dd('Transaction exception:', Helper::errorMsg($e));
            }
        }
    }
}
