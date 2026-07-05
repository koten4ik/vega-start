<?php

namespace Modules\SitePage\Commands;



use Modules\SitePage\Models\PageModel;
use Modules\SitePage\ViewModels\PageViewModel;
use Modules\ZSupport\App\Services\MetaTags;

class ViewSitePageCommand
{

	public function execute($path)
	{
		$page = PageModel::query()
            ->where('slug', $path)
            ->firstOrFail();

		MetaTags::addFromPage($page);

		return [
			'page' => PageViewModel::data($page),
		];
	}


}
