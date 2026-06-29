<?php


namespace Modules\ZSupport\App\Services;


use Illuminate\Support\Facades\Http;
use Modules\ZSupport\App\Services\Logger\Log;

class TelegramNotifier
{
	public static function sendModer(string $message): void
	{
		$token = config('services.telegram.moder_bot_token');
		$chatId = config('services.telegram.moder_bot_chat_id');

		self::send($token, $chatId, $message);
	}

	public static function sendDev(string $message): void
	{
		$token = config('services.telegram.dev_bot_token');
		$chatId = config('services.telegram.dev_bot_chat_id');

		self::send($token, $chatId, $message);
	}

	public static function send($token, $chatId, $message): void
	{
		$response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
			'chat_id' => $chatId,
			'text' => $message,
			'parse_mode' => 'HTML',
		]);

		$result = $response->json();
		if(isset($result['ok']) && $result['ok'] == false){
			Log::error('TelegramNotifier error', $result);
		}
		//dd($response->json());
	}
}
