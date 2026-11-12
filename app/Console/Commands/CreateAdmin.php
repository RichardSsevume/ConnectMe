<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email=admin@connectme.com} {password=Admin@12345} {--name=Connect Me Admin}';
    protected $description = 'Create or reset an administrator account (role, active status and verification are forced correctly)';

    public function handle(): int
    {
        $user = User::firstOrNew(['email' => $this->argument('email')]);
        $user->forceFill([
            'name' => $user->exists ? $user->name : $this->option('name'),
            'password' => $this->argument('password'),   // hashed by the model cast
            'role' => 'admin',
            'account_status' => 'active',
            'verification_status' => 'verified',
        ])->save();

        $this->info('Administrator ready: ' . $user->email . ' (password as given). Please change it after logging in.');

        return self::SUCCESS;
    }
}
