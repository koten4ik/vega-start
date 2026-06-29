<?php


namespace Modules\ZSupport\App\Services;


use Modules\SitePage\Models\PageModel;
use Modules\ZSupport\App\Services\Logger\Log;

class ScheduleCheckService
{
	private function getStorage()
	{
		//todo найти место для хранения получше
		$page = PageModel::where('module','index')->first();
		if(!$page) Log::error('не найдена page=index для проверки шедулера');
		return $page;
	}

	public function clock()
	{
		$data = $this->getStorage();
		$data->schedule_time = time();
		$data->save();
	}

	//todo вызывать на отдельном кроне
	public function check()
	{
		$data = self::getStorage();
		if($data->schedule_time < time()-60*3)
			Log::errorCritical('шедулер не работает!!!');
	}
}
