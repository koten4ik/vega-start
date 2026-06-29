<?php


namespace Modules\SiteMainPage\Commands;


use Illuminate\Support\Facades\Auth;
use Modules\Mlm\Models\Teasers;
use Modules\Post\Entities\PostType;
use Modules\Post\Models\TagModel;
use Modules\SiteMainPage\Filters\MainPageFilter;
use Modules\SiteMainPage\ViewModels\MainPageDiscussNowViewModel;
use Modules\SiteMainPage\ViewModels\MainPageFeedViewModel;
use Modules\SiteMainPage\ViewModels\MainPageNewsViewModel;
use Modules\SiteMainPage\ViewModels\MainPagePopAuthorsViewModel;
use Modules\SiteMainPage\ViewModels\MainPageRatingViewModel;
use Modules\SitePage\Enums\PageDomain;
use Modules\SitePage\Models\PageModel;
use Modules\ZSupport\App\Services\MetaTags;

class MainPageCommand
{

    public function execute($request)
    {
        $data = [];

        $path = '/' . ltrim($request->path(), '/');
        $page = PageModel::query()
            ->where('slug', $path)
            ->first();


        $data['page'] = $page ?? null;

        return $data;
    }

}
