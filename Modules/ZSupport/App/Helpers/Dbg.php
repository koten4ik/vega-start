<?php
/**
 * Debug Helper
 */

namespace Modules\ZSupport\App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dbg
{
	public static function dd($data)
	{
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		die();
	}

	public static function timeFromStart()
	{
		$start = 0;
		if (defined('LARAVEL_START')) $start = LARAVEL_START;
		elseif (defined('SCRIPT_START')) $start = SCRIPT_START;

		return intval((microtime(true) - $start) * 1000);
	}

	//todo назвать более корректно
	public static function toSql($query)
	{
		if (is_array($query)) {
			$sql = $query['query'];
			$bindings = $query['bindings'];
		} else {
			$sql = $query->toSql();
			$bindings = $query->getBindings();
		}

		$i = 0;
		return preg_replace_callback('/\?/', function () use (&$i, $bindings) {
			if (!array_key_exists($i, $bindings)) return '?'; // лишний ? в строке
			$binding = $bindings[$i++];
			return is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
		}, $sql);
	}

	public static function getSqlQueriesRaw()
	{
		$db_queries = DB::getQueryLog();
		return $db_queries;
	}

	public static function getSqlQueriesArray()
	{
		$arr = [];
		$db_queries = self::getSqlQueriesRaw();
		if (count($db_queries)) {
			foreach ($db_queries as $query) {
				$sql = self::toSql($query);
				$arr[] =
					[
						'query' => $sql,
						'time' => $query['time']
					];
			}
		}

		return $arr;
	}

	public static function getSqlQueriesString()
	{
		$str = '';
		$db_queries = self::getSqlQueriesArray();
		if (count($db_queries)) {
			foreach ($db_queries as $query) {
				$color = 'green';
				if($query['time']>1) $color = 'orange';
				if($query['time']>10) $color = 'red';
				$str .= '<span style="color: '.$color.'">'. $query['time'] . '</span> | ' . $query['query']  . '<br><br>';
			}
		}

		return $str;
	}

	//должен вызыватся перед рендером или в конце ответа
	public static function stats()
	{
		$arr = [];

		$db_queries = self::getSqlQueriesRaw();
		//self::dd($db_queries);

		$db_query_all_time = 0;
		if (count($db_queries)) {
			foreach ($db_queries as $query) {
				$db_query_all_time += $query['time'];
			}
		}

		$arr['server_time'] = Date::dateFull(time());
		$arr['response_time'] = self::timeFromStart();
		$arr['db_query_count'] = count($db_queries);
		$arr['db_query_all_time'] = intval($db_query_all_time);
		//$arr['db_queries'] = self::getSqlQueriesArray();

		return $arr;
	}
}


