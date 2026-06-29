<?php


namespace Modules\ZSystem\Commands;


use Modules\ZSupport\App\Exceptions\LogicException;
use Modules\ZSupport\App\Services\Mailer\Mailer;


class GetLetterCommand
{

	public function execute($request)
	{
		if(isset($request->template_id) == false || !$request->template_id)
			throw new LogicException('Укажите шаблон');

		$data = Mailer::testData($request->template_id);
		$data['template_id'] = $request->template_id;
		$data['no_save'] = true;
		$letter = Mailer::createMail($data);

		return  $letter;
	}
}
