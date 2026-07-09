<?php

namespace Modules\ZSystem\Http\Controllers;


use Illuminate\Http\Request;
use Modules\SiteSearch\Services\SearchService;
use Modules\ZSupport\App\Controllers\VegaController;
use Modules\ZSystem\Commands\ATemp3Command;
use Modules\ZSystem\Commands\ATempCommand;
use Modules\ZSystem\Commands\ATemp2Command;


class TempController extends VegaController
{
	public function temp(Request $request, ATempCommand $tempCommand)
	{
		return $tempCommand->execute($request);
	}

}
