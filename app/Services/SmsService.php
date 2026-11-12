<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Sends SMS through Africa's Talking (works in Uganda) when SMS_DRIVER=africastalking,
 * or Twilio when SMS_DRIVER=twilio. With SMS_DRIVER=log (default) the message is only written
 * to storage/logs/laravel.log so nothing breaks before you have gateway credentials.
 */
class SmsService
{
    public static function normalise(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }
        if (str_starts_with($digits, '0')) {
            $digits = '256' . substr($digits, 1);
        }

        return '+' . $digits;
    }

    public function send(?string $phone, string $message): bool
    {
        $to = self::normalise($phone);
        if (! $to) {
            return false;
        }

        $driver = config('services.sms.driver', 'log');

        try {
            if ($driver === 'africastalking') {
                $c = config('services.sms.africastalking');
                $host = ($c['username'] ?? '') === 'sandbox' ? 'api.sandbox.africastalking.com' : 'api.africastalking.com';
                $payload = ['username' => $c['username'], 'to' => $to, 'message' => $message];
                if (! empty($c['from'])) {
                    $payload['from'] = $c['from'];
                }
                $r = Http::withHeaders(['apiKey' => $c['key'], 'Accept' => 'application/json'])
                    ->asForm()->post("https://{$host}/version1/messaging", $payload);

                return $r->successful();
            }

            if ($driver === 'twilio') {
                $c = config('services.sms.twilio');
                $r = Http::withBasicAuth($c['sid'], $c['token'])->asForm()
                    ->post("https://api.twilio.com/2010-04-01/Accounts/{$c['sid']}/Messages.json",
                        ['To' => $to, 'From' => $c['from'], 'Body' => $message]);

                return $r->successful();
            }

            Log::info("[SMS to {$to}] {$message}");

            return true;
        } catch (\Throwable $e) {
            Log::warning('SMS failed: ' . $e->getMessage());

            return false;
        }
    }
}
