<?php
/**
 * Main Helper
 */

namespace Modules\ZSupport\App\Helpers;

use Carbon\Carbon;
use Modules\ZSupport\App\Services\Logger\Log;

class Date
{
	public static function toTimestamp($value)
	{
		$timestamp = null;

		if(is_string($value)){
			$timestamp = strtotime($value);
		}

		if($value instanceof Carbon){
			$timestamp = $value->timestamp;
		}

		if(is_integer($value)){
			$timestamp = $value;
		}

		if(is_integer($timestamp) == false){
			$timestamp = 'unknown format';
			Log::warning('unknown date format');
		}

		return $timestamp;
	}

	public static function dateFull($value)
	{
		if($value === null) return null;

		$timestamp = self::toTimestamp($value);

		if(is_string($timestamp))
		{
			$date = $timestamp;
		}
		else {
			$date = date('Y-m-d H:i:s', $timestamp);
		}

		return $date;
	}


	public static function months($num, $lang = 'ru')
	{
		$arr = array('01' => 'января', '02' => 'февраля', '03' => 'марта',
			'04' => 'апреля', '05' => 'мая', '06' => 'июня', '07' => 'июля', '08' => 'августа',
			'09' => 'сентября', '10' => 'октября', '11' => 'ноября', '12' => 'декабря');
		$arr_en = array('01' => 'January', '02' => 'February', '03' => 'March',
			'04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
			'09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
		return $lang == 'en' ? $arr_en[$num] : $arr[$num];
	}

	public static function months2($num, $lang = 'ru')
	{
		$arr = array('01' => 'январь', '02' => 'февраль', '03' => 'март',
			'04' => 'апрель', '05' => 'май', '06' => 'июнь', '07' => 'июль', '08' => 'август',
			'09' => 'сентябрь', '10' => 'октябрь', '11' => 'ноябрь', '12' => 'декабрь');
		$arr_en = array('01' => 'January', '02' => 'February', '03' => 'March',
			'04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
			'09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
		return $lang == 'en' ? $arr_en[$num] : $arr[$num];
	}

	public static function date_print($val, $format, $gmt_corr = false)
	{
		if ($val) {
			$val = $val + ($gmt_corr ? self::userGMT() : 0);
			return date($format, $val);
		}
	}

	public static function date_print2($val)
	{
		if (!$val) return;

		$start_of_yesterday = mktime(0, 0, 0, date('m'), date('d') - 1);
		$start_of_tomorrow = mktime(0, 0, 0, date('m'), date('d') + 1);
		$start_of_today = mktime(0, 0, 0);
		$length = time() - $val;

		$date = date('j.m.Y', $val);
		$date = explode('.', $date);

		if ($length < 3600 && $length >= 0) {
			return floor($length / 60) . ' ' . \Y::cnt('минута|минуты|минут', floor($length / 60));
		}
		if ($val > $start_of_today && $val < time())
			return floor($length / 3600) . ' ' . \Y::cnt('час|часа|часов', floor($length / 3600));

		if ($val < $start_of_today && $val > $start_of_yesterday)
			return 'Вчера';

		if ($date[2] == date('Y'))
			return $date[0] . ' ' . Y::months($date[1]);

		return $date[0] . ' ' . Y::months($date[1]) . ' ' . $date[2] . ' г.';
	}

	public static function date_print3($val)
	{
		if (!$val) return;

		$start_of_yesterday = mktime(0, 0, 0, date('m'), date('d') - 1);
		$start_of_tomorrow = mktime(0, 0, 0, date('m'), date('d') + 1);
		$start_of_today = mktime(0, 0, 0);
		$length = time() - $val;

		$date = date('j.m.Y.H.i', $val);
		$date = explode('.', $date);

		if ($val > $start_of_today)
			return 'Сегодня' . ' в ' . $date[3] . ':' . $date[4];

		if ($val < $start_of_today && $val > $start_of_yesterday)
			return 'Вчера' . ' в ' . $date[3] . ':' . $date[4];

		return $date[0] . '.' . ($date[1]) . '.' . $date[2] . ' в ' . $date[3] . ':' . $date[4];

	}

	public static function dayOfWeek($time)
	{
		$days = array(
			'Monday' => 'Понедельник',
			'Tuesday' => 'Вторник',
			'Wednesday' => 'Среда',
			'Thursday' => 'Четверг',
			'Friday' => 'Пятница',
			'Saturday' => 'Суббота',
			'Sunday' => 'Воскресенье',
		);
		return $days[date('l', $time)];
	}

	public static function weekNames($n = 0)
	{
		$arr = [
			1 => 'Пн',
			2 => 'Вт',
			3 => 'Ср',
			4 => 'Чт',
			5 => 'Пт',
			6 => 'Сб',
			7 => 'Вс',
		];
		if ($n > 0) return $arr[$n];
		else return $arr;
	}

	public static function weekDay($timestamp)
	{
		return self::weekNames(date('N', $timestamp));
	}

	public static function isToday($time)
	{
		$start_of_tomorrow = mktime(0, 0, 0, date('m'), date('d') + 1);
		$start_of_today = mktime(0, 0, 0);
		return $time >= $start_of_today && $time < $start_of_tomorrow;
	}

	public static function isYesterday($time)
	{
		$start_of_yesterday = mktime(0, 0, 0, date('m'), date('d') - 1);
		$start_of_today = mktime(0, 0, 0);
		return $time >= $start_of_yesterday && $time < $start_of_today;
	}

}


