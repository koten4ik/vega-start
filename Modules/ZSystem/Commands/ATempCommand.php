<?php


namespace Modules\ZSystem\Commands;


use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Comment\Models\CommentModel;
use Modules\Market\Commands\TestPurchaseDefaultProductCommand;
use Modules\Mlm\Commands\TestPurchasePackCommand;
use Modules\Mlm\Models\PackMlmModel;
use Modules\Mlm\Models\PackMlmOrder;
use Modules\Mlm\Services\MlmService;
use Modules\MlmBinary\Models\BinaryMlmModel;
use Modules\MlmBinary\Services\MlmBinaryService;
use Modules\MlmBonus\Models\BonusItemMlmModel;
use Modules\MlmBonus\Services\Bonus3Service;
use Modules\MlmBonus\Services\Bonus4Service;
use Modules\MlmBonus\Services\Bonus5Service;
use Modules\MlmBonus\Services\BonusService;
use Modules\MlmNetwork\Models\NetworkDataMlmModel;
use Modules\MlmNetwork\Models\NetworkMlmModel;
use Modules\MlmNetwork\Services\MlmNetworkService;
use Modules\Post\Models\ReactionPostModel;
use Modules\Post\Models\ViewPostModel;
use Modules\Post\Services\HypertextService;
use Modules\Post\ViewModels\PostViewModel;
use Modules\SiteSearch\Services\SearchService;
use Modules\User\Commands\DetectSuspiciousViewsCommand;
use Modules\User\Models\UserModel;
use Modules\UserAccountAuth\Services\OAuthAppleService;
use Modules\UserAccountRegister\Commands\CreateUserCommand;
use Modules\UserBadge\Entities\UserBadge;
use Modules\UserBadge\Models\UserBadgeRelationModel;
use Modules\UserBadge\Services\UserBadgeAddService;
use Modules\UserNotification\Models\UserNotificationModel;
use Modules\UserRating\Commands\CalcUserRatingCommand;
use Modules\UserSubscribe\Models\UserSubscribeAuthorRelationModel;
use Modules\UserTariff\Services\TariffProductPriceService;
use Modules\ZSupport\App\Commands\IndexElasticCommand;
use Modules\Post\Models\PostModel;
use Modules\MlmBonus\Enums\BonusTypeMlmEnum;
use Modules\ZSupport\App\Helpers\Excel;
use Modules\ZSupport\App\Services\Logger\Log;

class ATempCommand
{
    public function __construct()
    {
    }

    public function execute($request)
    {
        //if (UserRoleService::isAdmin(Auth::user()) === false) abort(405);
        //Log::info('asd');
        echo 12344778;

    }

}
