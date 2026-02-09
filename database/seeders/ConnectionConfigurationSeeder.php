<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\ConfigurationType;
use App\Models\ConnectionConfiguration;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConnectionConfigurationSeeder extends Seeder
{
    public function run()
    {
        $numRows = config('app.connection_configurations.seeder.num_rows');

        for ($i = 0; $i < $numRows; $i++) {
            /** @var ConfigurationType $configurationType */
            $configurationType = ConfigurationType::inRandomOrder()->first();

            switch ($configurationType->name) {
                case ConfigurationType::SHADOW_SOCKS:
                    $serversInCount = rand(1, config('app.connection_configurations.seeder.max_shadow_socks_servers_in'));
                    $serversIn = Server::whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                            $query->where('name', ServerType::SS);
                        })
                        ->inRandomOrder()
                        ->limit($serversInCount)
                        ->get();

                    $serversOutCount = rand(1, config('app.connection_configurations.seeder.max_shadow_socks_servers_out'));
                    $serversOut = Server::whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                            $query->where('name', ServerType::VPN_IO);
                        })
                        ->inRandomOrder()
                        ->limit($serversOutCount)
                        ->get();
                    break;
                case ConfigurationType::DOUBLE_VPN:
                    $serversIn = Server::whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                            $query->where('name', ServerType::VPN_IN);
                        })
                        ->inRandomOrder()
                        ->limit(1)
                        ->get();

                    $serversOutCount = rand(1, config('app.connection_configurations.seeder.max_double_vpn_servers_out'));
                    $serversOut = Server::whereHas(Server::RELATION_SERVER_TYPE, function ($query) {
                            $query->where('name', ServerType::VPN_OUT);
                        })
                        ->inRandomOrder()
                        ->limit($serversOutCount)
                        ->get();
                    break;
                default:
                    dd('Unknown server configuration type!', $configurationType);
            }

            $connectionConfigurationItem = [
                'name' => 'configuration-' . fake()->slug(1),
                'configuration_type_id' => $configurationType->id,
            ];

            try {
                DB::transaction(function () use ($connectionConfigurationItem, $serversIn, $serversOut) {
                    /** @var ConnectionConfiguration $connectionConfiguration */
                    $connectionConfiguration = (new ConnectionConfiguration)->create($connectionConfigurationItem);

                    foreach ($serversIn as $serverIn) {
                        $connectionConfiguration->serversIn()->attach($serverIn);
                    }
                    
                    foreach ($serversOut as $serverOut) {
                        $connectionConfiguration->serversOut()->attach($serverOut);
                    }

                    // If all operations succeed, the transaction is committed automatically
                });
            } catch (\Exception $e) {
                // If an exception occurred in the closure, all changes are rolled back
                dd('Transaction exception:', Helper::errorMsg($e));
            }
        }
    }
}
