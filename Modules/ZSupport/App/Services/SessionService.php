<?php

namespace Modules\ZSupport\App\Services;


use Illuminate\Support\Facades\Session;

class SessionService
{
	public static function setFlash($key,$data)
	{
		$name = explode('.',$key);
		if(count($name)>1){
			$arr = [
				$name[0] => Session::get($name[0])
			];
			$arr[$name[0]][$name[1]] = $data;
		}
		else{
			$arr[$key] = $data;
		}

		Session::put($arr);
	}

	public static function getFlash($key)
	{
		$data = Session::get($key);
		Session::forget($key);
		Session::save();

		return $data;
	}

}
