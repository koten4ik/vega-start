<?php

namespace Modules\ZSupport\App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginAsService
{
    public static function buildSsoUrl($user, string $targetApp, ?int $marketOrderId = null): string
    {
        $secret = env('SSO_SECRET');

        $expires = now()->addMinutes(5)->timestamp;
        $payload = $user->email . '|' . $expires ;
        if ($marketOrderId) {
            $payload .= '|'.$marketOrderId;
        }
        $hash = hash('sha256', $payload.'|'.$secret);

        $baseUrl = match ($targetApp) {
            'main' => env('APP_URL'),
            'market' => env('APP_MARKET_URL'),
        };

        return $baseUrl . '/loginas?' . http_build_query([
                'email' => $user->email,
                'expires' => $expires,
                'order_id' => $marketOrderId,
                'hash' => $hash,
            ]);
    }

    public static function validateSsoRequest(Request $request): string
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'hash' => 'required|string',
            'expires' => 'required|integer',
        ])->validate();

        // Проверка TTL
        if ((int)$request->expires < now()->timestamp) {
            throw new HttpException(403, 'SSO expired');
        }

        $secret = env('SSO_SECRET');

        $payload = $request->email . '|' . $request->expires;

        if ($request->filled('order_id')) {
            $payload .= '|' . $request->order_id;
        }

        $expected = hash('sha256', $payload . '|' . $secret);

        if (!hash_equals($expected, $request->hash)) {
            throw new HttpException(403, 'Invalid SSO signature');
        }

        return $request->email;
    }
}
