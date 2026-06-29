<?php


namespace Modules\ZSystem\Commands;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\ZSupport\App\Helpers\Dbg;


class ViewCountDiagramCommand
{
	public function __construct()
	{
	}

	public function execute($request)
	{
		$table = $request->table ?? 'a_requests';



		$time = now()->subDay();
		$start = Carbon::now()->subDay()->startOfMinute();
		$end = Carbon::now()->startOfMinute();

		if (isset($request->d)) {
			$time = now()->subDays($request->d);
			$start = Carbon::now()->subDays($request->d)->startOfMinute();
		}
		if (isset($request->h)) {
			$time = now()->subHours($request->h);
			$start = Carbon::now()->subHours($request->h)->startOfMinute();
		}

		$records = DB::table($table)
			->selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as minute, COUNT(*) as total")
			->where('created_at', '>=', $time)
			->groupBy('minute')
			->pluck('total', 'minute'); // получим в виде ['2025-04-09 14:25' => 3, ...]


		$labels = [];
		$values = [];

		while ($start <= $end) {
			$minuteKey = $start->format('Y-m-d H:i');
			$labels[] = $minuteKey;
			$values[] = $records[$minuteKey] ?? 0;
			$start->addMinute();
		}





		$unique_time1 = now()->subDays($request->d ?? 1);
		$unique_time2 = now()->startOfDay();
		//$unique_time2 = now()->subHours(3);


		// uuid_count==0 - те кто более одного запроса сделал
		// и uuid_count==1 - те кто впервые зашли на сайт
		// для uuid_count==1 в цикле:
		// новые - count(uuid)>1,
		// случайные (count(uuid)==1 и count(uuid2)==1)
		// боты/хаки (count(uuid)==1 и count(uuid2)>1)
		// при боты/хаки: если count(uuid2) маленький - возможно просто совпадение отпечатков, если большой то точно боты/хаки

		$print_arr = [];


		//просто уники по uuid(люди+боты/хаки)
		$t1 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time1)->count('uuid');
		$t2 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time2)->count('uuid');
		$print_arr[] = 'uuid - уники за сутки: ' . $t1 . ' --------- уники за сегодня: ' . $t2;

		//уники по uuid2, потом может уберу это
		$t1 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time1)->count('uuid2');
		$t2 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time2)->count('uuid2');
		$print_arr[] = 'uuid2 - уники за сутки: ' . $t1 . ' --------- уники за сегодня: ' . $t2;
		$print_arr[] = '';



		//те у кого работают куки
		$t1 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time1)->where('uuid_count', 0)->count('uuid');
		$t2 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time2)->where('uuid_count', 0)->count('uuid');
		$print_arr[] = 'uuid(люди) - уники за сутки: ' . $t1 . ' --------- уники за сегодня: ' . $t2;

		//те кто впервые зашли на сайт - быстрый способ
		$t1 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time1)->where('uuid_count', 1)->count('uuid');
		$t2 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time2)->where('uuid_count', 1)->count('uuid');
		$print_arr[] = 'uuid(все новые) - уники за сутки: ' . $t1 . ' --------- уники за сегодня: ' . $t2;

		//те кто впервые зашли на сайт - подробно но медленно
		if(isset($request->stat)) {
			$t1 = DB::table('a_requests')->where('created_at', '>=', $unique_time1)->where('uuid_count', 1)->get();
			$t2 = DB::table('a_requests')->where('created_at', '>=', $unique_time2)->where('uuid_count', 1)->get();
			$arr = [[0, 0, 0], [0, 0, 0]];
			foreach ($t1 as $elem) {
				$uuid_count = DB::table('a_requests')->where('created_at', '>=', $unique_time1)->where('uuid', $elem->uuid)->count();
				$uuid2_count = DB::table('a_requests')->where('created_at', '>=', $unique_time1)->where('uuid2', $elem->uuid2)->count();
				if ($uuid_count > 1) $arr[0][0]++;
				else {
					if ($uuid2_count == 1) $arr[0][1]++;
					else $arr[0][2]++;
				}
			}
			$arrr = [];
			//для "сегодня" с отчетом, можно такое сделать и для "суток"
			foreach ($t2 as $elem) {
				$uuid_count = DB::table('a_requests')->where('created_at', '>=', $unique_time2)->where('uuid', $elem->uuid)->count();
				$uuid2_count = DB::table('a_requests')->where('created_at', '>=', $unique_time2)->where('uuid2', $elem->uuid2)->count();
				if ($uuid_count > 1) {
					$arr[1][0]++;
					$type = 1;
				} else {
					if ($uuid2_count == 1) {
						$arr[1][1]++;
						$type = 2;
					} else {
						$arr[1][2]++;
						$type = 3;
					}
				}
				if ($type == 2) $arrr[] = 'url - ' . $elem->url . ' ua - ' . $elem->user_agent . ' ip - ' . $elem->ip;
				elseif ($type == 3) $arrr[] = 'uuid2('.$uuid2_count.') - ' . $elem->uuid2 . ' ua - ' . $elem->user_agent . ' ip - ' . $elem->ip;
			}
			$print_arr[] = 'uuid(новые подробно) - уники за сутки: ' . $arr[0][0] . '/' . $arr[0][1] . '/' . $arr[0][2] . ' --------- уники за сегодня: ' . $arr[1][0] . '/' . $arr[1][1] . '/' . $arr[1][2];
			$print_arr[] = '';
			$print_arr[] = 'отчет случайным и подозрительным по "сегодня"';
			foreach ($arrr as $elem) $print_arr[] = $elem;
		}



		//подсчет запросов
		$t1 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time1)->count();
		$t2 = DB::table('a_requests')->distinct()->where('created_at', '>=', $unique_time2)->count();
		$print_arr[] = '';
		$print_arr[] = 'запросов за сутки: ' . $t1 . ' --------- запросов за сегодня: ' . $t2;


		return [
			'print_arr' => $print_arr,

			'labels' => $labels,
			'values' => $values,
		];
	}
}
