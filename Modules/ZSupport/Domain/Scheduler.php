<?php


namespace Modules\ZSupport\Domain;


use Illuminate\Console\Scheduling\Schedule;
use Modules\BusinessSubscription\Commands\ExpireSubscriptionsCommand;
use Modules\Post\Commands\SetPopularFlagCommand;
use Modules\SiteSearch\Services\SearchService;
use Modules\User\Commands\DeleteOldUserActionsCommand;
use Modules\Post\Commands\DeleteOldViewCheckPostCommand;
use Modules\User\Commands\DetectSuspiciousActionsCommand;
use Modules\User\Commands\DetectSuspiciousViewsCommand;
use Modules\UserRating\Commands\CalcUserRatingCommand;
use Modules\ZSupport\App\Commands\DeleteOldLogsCommand;
use Modules\ZSupport\App\Commands\DeleteOldMailsCommand;
use Modules\ZSupport\App\Commands\DeleteOldRequestsCommand;
use Modules\ZSupport\App\Commands\DeleteOldSessionStatsCommand;
use Modules\ZSupport\App\Commands\IndexElasticCommand;
use Modules\ZSupport\App\Commands\MailPushCommand;
use Modules\ZSupport\App\Commands\NotifyErrors500Command;
use Modules\ZSupport\App\Services\Logger\Log;
use Modules\ZSupport\App\Services\ScheduleCheckService;
use Modules\UserNotification\Commands\DeleteOldNotificationsCommand;
use Modules\UserAccountRegister\Commands\DeleteNoActiveUserCommand;
use Modules\Post\Commands\SendBlogPostDraftReminderCommand;
use Modules\Post\Commands\SendBlogPostTelegramCommand;
use Modules\Post\Commands\SendBlogPostTelegramFreshCommand;
use Modules\Post\Commands\SendBlogPostWeeklyCommand;

//регистрация - bootstrap/app.php
/*->withSchedule(function (Schedule $schedule) {
	Scheduler::run($schedule);
})*/
class Scheduler
{
	//* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
	public static function run(Schedule $schedule)
	{
		if (env('APP_ENV') == 'production') {

		}


		// всяко разно
		//$schedule->call([new CalcUserRatingCommand(), 'execute'])->hourly();


		// очистки
		//$schedule->call([new DeleteOldUserActionsCommand(), 'execute'])->daily();


		//команды приложения
		$schedule->call([new MailPushCommand(), 'execute'])->everyMinute();
		$schedule->call([new NotifyErrors500Command(), 'execute'])->dailyAt('10:00');
		$schedule->call([new DeleteOldRequestsCommand(), 'execute'])->daily();
		$schedule->call([new DeleteOldLogsCommand(), 'execute'])->daily();
		$schedule->call([new DeleteOldSessionStatsCommand(), 'execute'])->daily();


		//должно быть в конце вызвано
		//по ходу это не актуально так как в новой версии лары если крашится одна команда - крон продолжает работу
		$schedule->call([new ScheduleCheckService(), 'clock'])->everyMinute();
	}
}
