<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

/**
 * Makes sure an administrator account always exists (the seeder was never run on
 * some installs, which is why admin login failed - there was no admin to log in as).
 * Log in with admin@connectme.com / Admin@12345, then change the password.
 */
return new class extends Migration
{
    public function up(): void
    {
        $admin = User::where('email', 'admin@connectme.com')->first();

        if (! $admin) {
            User::create([
                'name' => 'Connect Me Admin', 'email' => 'admin@connectme.com', 'password' => 'Admin@12345',
                'role' => 'admin', 'verification_status' => 'verified', 'account_status' => 'active',
                'phone' => '+256 700 000 000', 'location' => 'Kampala',
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
