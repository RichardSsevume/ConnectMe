<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\WelcomeAccount;
use Illuminate\Support\Facades\Log;

class AccountNotifier
{
    /** Email + SMS confirmation after sign-up. Never lets a notification failure break registration. */
    public static function welcome(User $user): void
    {
        try {
            $user->notify(new WelcomeAccount());
        } catch (\Throwable $e) {
            Log::warning('Welcome email failed: ' . $e->getMessage());
        }

        $name = $user->isEmployer() ? ($user->company_name ?: $user->name) : $user->firstName();
        app(SmsService::class)->send(
            $user->phone,
            "Hello {$name}, your Connect Me account was created successfully. Log in at " . url('/login') . ' to get started.'
        );
    }
}
