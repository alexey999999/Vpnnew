<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServerSeeder extends Seeder
{
    public function run()
    {
        $encryptionMethods = [
            'AES',
            'DES',
            '3DES',
            'Blowfish',
            'RC4',
            'RC5',
            'RC6',
            'ChaCha20',
            'RSA',
            'ECC',
            'MD5',
            'SHA-1',
            'SHA-256',
            'SHA-512',
            'SHA-3',
        ];
        $numRows = 100;
        $data = [];
        
        for ($i = 0; $i < $numRows; $i++) {
            /** @var ServerType $serverType */
            $serverType = ServerType::inRandomOrder()->first();
            /** @var Country $country */
            $country = Country::inRandomOrder()->first();

            $item = [
                'name' => 'server-' . fake()->slug(1),
                'server_type_id' => $serverType->id,
                'ipv4' => fake()->ipv4(),
                'protocol_version' => fake()->randomFloat(2, 0, 10),
                'country_id' => $country->id,
                'url' => fake()->url(),
                'main_token' => Str::password(20),
                'remote_token' => Str::password(20),
                'current_load' => 0,
                'avg_load' => 0,
            ];
            
            if ($serverType->name === 'ss') {
                $item = array_merge($item, [
                    'port' => mt_rand(49152, 65535),//Dynamic/Ephemeral ports diapason
                    'password' => Str::password(20),
                    'encryption_method' => $encryptionMethods[array_rand($encryptionMethods)],
                ]);
            }
            
            $data[] = $item;
        }
        
        foreach ($data as $item) {
            try {
                if (!is_array($item)) {
                    dd('Item is not array!', $item);
                }
                (new Server)->fill($item)->save();
            } catch (\Exception $e) {
                dd($e->getMessage(), $item);
            }
        }
    }
}
