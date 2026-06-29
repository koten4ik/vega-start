<?php

namespace Modules\ZSupport\App\Services\RedSms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\ZSupport\App\Models\SmsLog;

class RedSmsService
{
    private const API_URL = 'https://cp.redsms.ru/api/message';

    public function sendPhoneVerificationCode(int $phone, int $code, int $userId): bool
    {
        return $this->send($phone, $code, 'fcall', $userId);
    }

    /**
     * Wait Call
     * https://docs.redsms.ru/http/send-message-wait-call/
     */
    public function sendWaitCall(int $phone, int $userId): array
    {
        $logData = $this->makeBaseLog($phone, 'wait_call', $userId);

        try {
            [$login, $secret, $ts] = $this->makeAuthHeaders();

            if (!$login || !$secret || !$ts) {
                $this->fail(
                    $logData,
                    'Не указан конфиг для сервиса отправки wait call.'
                );

                return [
                    'success' => false,
                    'phone' => null,
                    'session_id' => null,
                ];
            }

            $requestData = [
                'route' => 'wcall',
                'to' => $phone,
            ];

            $response = Http::withHeaders([
                'login' => $login,
                'ts' => $ts,
                'secret' => $secret,
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->post(self::API_URL, $requestData);

            if (!$response->successful()) {
                $this->fail(
                    $logData,
                    $response->json(),
                    $requestData
                );

                return [
                    'success' => false,
                    'phone' => null,
                    'session_id' => null,
                ];
            }

            $responseData = $response->json();

            $callNumber = $responseData['items'][0]['replacedFrom'] ?? null;
            $sessionId = $responseData['items'][0]['uuid'] ?? null;

            if (!$callNumber || !$sessionId) {
                $this->fail(
                    $logData,
                    'WaitCall номер или session_id не получен',
                    $responseData
                );

                return [
                    'success' => false,
                    'phone' => null,
                    'session_id' => null,
                ];
            }

            $logData['sent'] = true;
            $logData['error'] = null;

            $this->saveLog($logData);

            return [
                'success' => true,
                'phone' => $callNumber,
                'session_id' => $sessionId,
            ];

        } catch (\Throwable $e) {
            $this->fail(
                $logData,
                $e->getMessage(),
                $requestData ?? null
            );

            return [
                'success' => false,
                'phone' => null,
                'session_id' => null,
            ];
        }
    }

    /**
     * Получить статус сообщения / wait call
     *
     * https://docs.redsms.ru/http/api/get-message/
     */
    public function getMessageStatus(string $messageId): ?array
    {
        try {
            [$login, $secret, $ts] = $this->makeAuthHeaders();

            if (!$login || !$secret || !$ts) {

                return null;
            }

            $response = Http::withHeaders([
                'login' => $login,
                'ts' => $ts,
                'secret' => $secret,
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->get(self::API_URL . '/'.$messageId);

            if (!$response->successful()) {
                return null;
            }

            return $response->json();

        } catch (\Throwable $e) {
            return null;
        }
    }

    /*
     * https://docs.redsms.ru/http/send-message-fcall/
     *  */
    private function send(int $phone, string $message, string $method = 'sms', ?int $userId = null): bool
    {
        $logData = $this->makeBaseLog($phone, $message, $userId);
        $requestData = $this->makeRequestData($phone, $message, $method);

        try {
            [$login, $secret, $ts] = $this->makeAuthHeaders();

            if (!$login || !$secret || !$ts) {
                return $this->fail($logData, 'Не указан конфиг для сервиса отправки смс.');
            }

            $response = Http::withHeaders([
                'login' => $login,
                'ts' => $ts,
                'secret' => $secret,
                'Content-Type' => 'application/json',
            ])
                ->timeout(30)
                ->post(self::API_URL, $requestData);

            if (!$response->successful()) {
                return $this->fail(
                    $logData,
                    $response->json(),
                    $requestData
                );
            }

            return $this->success($logData);

        } catch (\Throwable $e) {
            return $this->fail(
                $logData,
                $e->getMessage(),
                $requestData ?? null
            );
        }
    }

    private function makeAuthHeaders(): array
    {
        $login = config('sms.red_sms_login');
        $apiKey = config('sms.red_sms_api_key');

        if (!$login || !$apiKey) {
            return [null, null, null];
        }

        $ts = 'ts-' . time();
        $secret = md5($ts . $apiKey);

        return [$login, $secret, $ts];
    }

    private function makeRequestData(int $phone, string $message, string $method = 'sms'): array
    {
        return [
            'route' => $method,
            'to' => $phone,
            'text' => $message,
        ];
    }

    private function makeBaseLog(int $phone, string $message, ?int $userId): array
    {
        return [
            'phone' => $phone,
            'message' => $message,
            'user_id' => $userId,
            'sent' => false,
            'error' => null,
        ];
    }

    private function success(array $logData): bool
    {
        $logData['sent'] = true;
        $logData['error'] = null;

        $this->saveLog($logData);

        return true;
    }

    private function fail(array $logData, $error, ?array $request = null): bool
    {
        $logData['error'] = is_array($error)
            ? json_encode($error, JSON_UNESCAPED_UNICODE)
            : $error;

        $this->saveLog($logData);

        Log::error('REDSMS sending failed', [
            'error' => $error,
            'request' => $request,
        ]);

        return false;
    }

    private function saveLog(array $logData): void
    {
        SmsLog::create($logData);
    }
}
