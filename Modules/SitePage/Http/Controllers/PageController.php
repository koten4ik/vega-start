<?php

namespace Modules\SitePage\Http\Controllers;

use App\OpenGraph;
use Modules\SitePage\Commands\StoreSitePageCommand;
use Modules\SitePage\Commands\UpdateSitePageCommand;
use Modules\SitePage\Commands\ViewSitePageCommand;
use Modules\ZSupport\App\Controllers\VegaController;
use Illuminate\Http\Request;

class PageController extends VegaController
{
    public function viewPage(Request $request, ViewSitePageCommand $viewSitePageCommand)
    {
        $path = $request->path();
		$data = $viewSitePageCommand->execute($path);

        return $this->render($this->getModuleName() . '::page', $data);
    }


}
