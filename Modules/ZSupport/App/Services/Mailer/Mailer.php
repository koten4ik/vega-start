<?php

namespace Modules\ZSupport\App\Services\Mailer;

use Carbon\Carbon;
use Modules\ZSupport\App\Exceptions\LogicException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Modules\ZSupport\App\Services\Config;
use Modules\ZSupport\App\Models\LetterModel;
use Modules\ZSupport\App\Models\LetterTemplateModel;

class Mailer
{
	public static function createMail($data)
	{
		$subject = null;
		$body = null;
		$fromAddress = null;
		$fromName = null;
		$to = null;

		if (isset($data['template_id']))
		{
			$template = LetterTemplateModel::where('name', $data['template_id'])->first();
			if (!$template) {
				throw new LogicException(
					'Письмо не отправлено(Mailer::createMail) - шаблон не найден: ' . $data['template_id']
				);
			}

			$subject = $template->subject;
			$body = $template->body;
			$fromAddress = $template->sender;
			$fromName = $template->sender_name;
			$to = $template->receiver;
			if (View::exists('emails.' . $template->name)) {
				$body = view('emails.' . $template->name, ['vals'=>$data['vals']]);
			}

		} else {
			$subject = $data['subject'] ?? '';
			$body = $data['body'] ?? '';
			$fromAddress = $data['fromAddress'] ?? null;
			$fromName = $data['fromName'] ?? null;
		}

		// Если явно передан адрес получателя, используем его
		if (isset($data['to'])) {
			$to = $data['to'];
		}

		if (!$fromAddress) $fromAddress = Config::get('sender') ?? env('MAIL_FROM_ADDRESS');
		if (!$fromName) $fromName = Config::get('sender_name') ?? env('MAIL_FROM_NAME');

		// Fallback на support@aaa.aaa используется только если:
		// 1. Адрес получателя не был явно передан в $data['to']
		// 2. И в шаблоне тоже не указан receiver
		// Это предотвращает отправку пользовательских писем на адрес саппорта
		if (!$to && !isset($data['to'])) {
			$to = Config::get('receiver');
		}

		// Валидация email: если адрес пустой или невалидный, выбрасываем исключение
		// Это предотвращает отправку писем на некорректные адреса
		if (isset($data['to']) && (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL))) {
			throw new LogicException(
				'Письмо не отправлено(Mailer::createMail) - некорректный адрес получателя: ' . ($to ?: 'пустой')
			);
		}

		if (isset($data['vals']))
			foreach ($data['vals'] as $name => $val) {
				$subject = str_replace('{' . $name . '}', $val, $subject);
				$body = str_replace('{' . $name . '}', $val, $body);
			}

		$letter = new LetterModel();
		$letter->subject = $subject ?? 'null';
		$letter->text = $body ?? 'null';
		$letter->sender = $fromAddress ?? 'null';
		$letter->sender_name = $fromName ?? 'null';
		$letter->receiver = $to ?? 'null';
		$letter->date = date('Y-m-d H:i:s');

		if (isset($data['no_save']) == false)
			$letter->save();

		return $letter;
	}

	public static function pushMail($letter, $data = null)
	{
		if ($letter) {
			$letter->date_sent = date('Y-m-d H:i:s');
			if (isset($data['no_save']) == false)
				$letter->save();

			$to = explode(',', $letter->receiver);
			$mailable = new SendMail([
				'subject' => $letter->subject,
				'body' => $letter->text,
				'fromAddress' => $letter->sender,
				'fromName' => $letter->sender_name,
			]);
			if (isset($data['replyToEmail']))
				$mailable->replyTo($data['replyToEmail'], $data['replyToName'] ?? '');

			if ($to)
				Mail::to($to)->send($mailable);
		}
	}

	public static function sendMail($data)
	{
		$letter = self::createMail($data);
		self::pushMail($letter, $data);
	}


	//todo вынести это отсюда!
	public static function testData($template_id)
	{
		$data = [];

		if($template_id=='email_confirm'){
			$data['vals'] = [
				'name' => 'testName',
				'email' => 'test@mail.com',
				'password' => 'testPass',
				'activation_url' => 'activation_url'
			];
		}
		if($template_id=='recovery'){
			$data['vals'] = [
				'recovery_link' => 'http://test.com',
			];
		}
		if($template_id=='change_password'){
			$data['vals'] = [
				'email' => 'test@mail.com',
				'password' => 'testPass'
			];
		}



		return $data;
	}

}


