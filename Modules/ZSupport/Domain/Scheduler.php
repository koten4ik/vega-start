<?php


namespace Modules\ZSupport\Domain;


use Illuminate\Console\Scheduling\Schedule;
use Modules\ZSupport\App\Commands\DeleteOldLogsCommand;
use Modules\ZSupport\App\Commands\DeleteOldMailsCommand;
use Modules\ZSupport\App\Commands\DeleteOldRequestsCommand;
use Modules\ZSupport\App\Commands\MailPushCommand;
use Modules\ZSupport\App\Commands\NotifyErrors500Command;
use Modules\ZSupport\App\Services\ScheduleCheckService;

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
