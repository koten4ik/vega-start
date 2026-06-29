<?php

namespace Modules\SiteMainPage\Http\Controllers;

use Modules\SiteMainPage\Commands\MainPageCommand;
use Modules\ZSupport\App\Controllers\VegaController;
use Illuminate\Http\Request;

class MainPageController extends VegaController
{

	public function indexPage(Request $request, MainPageCommand $mainPageCommand)
	{
		$data = $mainPageCommand->execute($request);

		return $this->render($this->getModuleName() . '::main', $data);
	}

}
