<?php

namespace Modules\ZSupport\App\Services;


use Modules\ZSupport\App\Services\Config;

class PaginateService
{
	public static function processBase($request, $page_size = 0)
	{
		if($page_size === 0) {
			$page_size = Config::get('page_size');
		}
		if(isset($request['page_size'])){
			$page_size = $request['page_size'];
		}
		$current_page = $request['current_page'] ?? 0;
		$offset = self::calcOffset($current_page, $page_size);

		return [
			'next_page_offset' => self::calcOffset($current_page+1, $page_size),
			'offset' => $offset,
			'page_size' => $page_size,
		];
	}

	public static function calcOffset($current_page, $page_size)
	{
		$offset = 0;
		if ($current_page > 0) {
			$offset = $current_page * $page_size;
		}
		return $offset;
	}

    public static function process($query, $request, $page_size = 0)
	{
		$base = self::processBase($request, $page_size);

		$items_all = $query->count();
		if ($base['offset'] > 0) {
			$query->offset($base['offset']);
		}
		$query->limit($base['page_size']);

		if ($items_all > $base['next_page_offset']) {
			return true;
		}

		return false;
	}
}
