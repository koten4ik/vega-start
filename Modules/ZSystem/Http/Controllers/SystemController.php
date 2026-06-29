<?php

namespace Modules\ZSystem\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSystem\Commands\ATempCommand;
use Modules\ZSystem\Commands\GetLetterCommand;
use Modules\ZSystem\Commands\SendMailCommand;
use Modules\ZSystem\Commands\StatsCommand;
use Modules\ZSystem\Commands\UpdateSystemCommand;
use Modules\ZSystem\Commands\ViewCountDiagram3Command;
use Modules\ZSystem\Commands\ViewCountDiagram2Command;
use Modules\ZSystem\Commands\ViewCountDiagramCommand;
use Modules\User\Services\UserRoleService;
use Modules\Post\Services\BlogPostWeeklySender;
use Modules\Post\Services\BlogPostDraftSender;


class SystemController extends VegaController
{


	public function update(Request $request, UpdateSystemCommand $updateSystemCommand)
	{
		//if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		$data = $updateSystemCommand->execute($request);

		return view($this->getModuleName() . '::update', ['data' => $data]);
	}

	public function testPost()
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		return view($this->getModuleName() . '::test_post', []);
	}

	public function countDiagram(Request $request, ViewCountDiagramCommand $viewCountDiagramCommand)
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		$data = $viewCountDiagramCommand->execute($request);

		return view($this->getModuleName() . '::count_diagram', $data);
	}

	public function countDiagram2(Request $request, ViewCountDiagram2Command $viewCountDiagramCommand)
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		$data = $viewCountDiagramCommand->execute($request);

		return view($this->getModuleName() . '::count_diagram', $data);
	}

	public function countDiagram3(Request $request, ViewCountDiagram3Command $viewCountDiagramCommand)
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		$data = $viewCountDiagramCommand->execute($request);

		return view($this->getModuleName() . '::count_diagram', $data);
	}


	public function mailBody(Request $request, GetLetterCommand $getLetterCommand)
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		//todo в mail_send на против выбора шаблонов сделать ссылку та этот урл с подстановкой шаблона из селекта
		$letter = $getLetterCommand->execute($request);

		echo $letter->text;
	}

	public function mailSend(Request $request, SendMailCommand $sendMailCommand)
	{
		if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);

		$data = $sendMailCommand->execute($request);

		return view($this->getModuleName() . '::mail_send', [
			'rezult' => $data['rezult'],
		]);
	}
}
