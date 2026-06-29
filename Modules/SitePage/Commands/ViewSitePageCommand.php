<?php

namespace Modules\SitePage\Commands;



use Modules\Post\Services\HypertextService;
use Modules\SitePage\Enums\PageDomain;
use Modules\SitePage\Models\PageModel;
use Modules\ZSupport\App\Services\MetaTags;

class ViewSitePageCommand
{

	public function execute($path)
	{
		$page = PageModel::query()
            ->where('url',$path)
            ->firstOrFail();

		MetaTags::addFromPage($page);

		return [
			'page' => $page,
		];
	}


}
