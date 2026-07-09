<?php


namespace Modules\SiteMainPage\Commands;



use Modules\SitePage\Models\PageModel;

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
