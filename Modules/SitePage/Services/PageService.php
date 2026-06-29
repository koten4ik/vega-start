<?php


namespace Modules\SitePage\Services;


use Modules\PostFavorite\Queries\FavoritesQuery;
use Modules\SitePage\Models\PageModel;
use Modules\SiteSearch\Services\SearchService;
use Modules\UserAccountAuth\Commands\OAuth2GetTokenCommand;
use Modules\UserAccountAuth\Services\OAuthTgService;
use Modules\UserAccountAuth\Services\OAuthVKService;
use Modules\UserNotification\Models\UserNotificationModel;
use Modules\UserNotification\Services\UserNotificationService;
use Modules\UserSubscribe\Models\UserSubscribeAuthorRelationModel;

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
