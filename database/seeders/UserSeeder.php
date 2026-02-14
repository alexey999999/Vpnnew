<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Tariff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        $numRows = config('app.users.seeder.num_rows');

        for ($i = 0; $i < $numRows; $i++) {
            $tariffsCount = rand(1, config('app.users.seeder.max_tariffs'));

            /** @var Tariff $tariffs */
            $tariffs = Tariff::inRandomOrder()
                ->limit($tariffsCount)
                ->get();

            try {
                DB::transaction(function () use ($tariffs) {
                    $email = fake()->email();
                    /** @var User $tariff */
                    $user = (new User())->create([
                        'name' => explode('@', $email)[0],
                        'email' => $email,
                        'password' => fake()->password(),
                        'is_admin' => false,
                    ]);
                    
                    $attachData = [];
                    
                    foreach ($tariffs as $tariff) {
                        $isActive = rand(0,1);
                        $now = now();

                        $attachData[] = [
                            'tariff_id' => $tariff->id,
                            'user_id' => $user->id,
                            'active_to' => $isActive ? $now->addDays(rand(1,10)) : $now->subDays(rand(1,10)),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    $user->tariffs()->attach($attachData);

                    // If all operations succeed, the transaction is committed automatically
                });
            } catch (\Exception $e) {
                // If an exception occurred in the closure, all changes are rolled back
                dd('Transaction exception:', Helper::errorMsg($e));
            }
        }
    }
}
