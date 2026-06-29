<?php


namespace Modules\ZSystem\Commands;


use Modules\ZSupport\App\Services\Mailer\Mailer;


class SendMailCommand
{

	public function execute($request)
	{
		$rezult = '';

		if ($request->submit == 1) {
			if (!$request->email) {
				$rezult = 'Укажите email!';
			}
			elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL) === false){
				$rezult = 'Не корректный email!';
			}
			else {
				/*$letter = Mailer::createMail([
					'to' => $request->email,
					'subject' => 'Проверка отправки почты',
					'body' => view('mail.send', ['body' => 'Тескст для тестового письма'])
				]);*/
				$getLetterCommand = new GetLetterCommand();
				$letter = $getLetterCommand->execute($request);

				$letter->receiver = $request->email;
				Mailer::pushMail($letter, ['no_save' => false]);
				$rezult = 'Письмо отправлено';
			}
		}

		return [
			'rezult' => $rezult,
		];
	}
}
