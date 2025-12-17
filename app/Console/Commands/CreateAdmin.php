<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin. Run command `php artisan create-admin {email} {password}`, where {email} - your email, {password} - your password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::create([
            'name' => explode('@', $this->argument('email'))[0],
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
            'is_admin' => true,
        ]);

        $this->info('Admin successfully created!');
        $this->comment('Login: ' . $this->argument('email'));
        $this->comment('Password: ' . $this->argument('password'));
    }
}
