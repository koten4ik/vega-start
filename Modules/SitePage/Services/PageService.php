<?php


namespace Modules\SitePage\Services;


use Modules\SitePage\Models\PageModel;

class PageService
{
    private static $pages = null;

    public static function byModule($module)
    {
        if (self::$pages == null) {
            self::$pages = PageModel::display()->get();
        }
        $page = self::$pages->where('module', $module)->first();

        //if($page==null) abort(404);

        return $page;
    }
}
