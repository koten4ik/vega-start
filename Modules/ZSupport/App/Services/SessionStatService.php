<?php

namespace Modules\ZSupport\App\Services;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\User\Services\UserService;
use Modules\ZSupport\App\Models\SessionStatModel;

class SessionStatService
{

	public static function touchSession()
	{
		$session_query = SessionStatModel::query();
		$user_id = null;
		$uuid_is_new = -1;
		$uuid = UserService::uuid($uuid_is_new);
		$is_user = true;

		if(Auth::id()) {
			$user_id = Auth::id();
			$session_query->where('user_id', $user_id);
		}
		else{
			//юзер через брауз, не бот, так как есть кука
			if($uuid_is_new === 0){
				$session_query->where('uuid', $uuid);
			}
			else{
				//помечаем что бот
				$is_user = false;
			}
		}

		//время устаревания
		$sub_time = Carbon::now()->subDay();
		$session_query->where('last_visit_at', '>', $sub_time);

		if($is_user) {
			$fresh_session = $session_query->first();

			if ($fresh_session) {
				$fresh_session->last_visit_at = Carbon::now();
				$fresh_session->request_count++;
				$fresh_session->save();
			} else {
				$new_session = new SessionStatModel();
				$new_session->created_at = Carbon::now();
				$new_session->last_visit_at = Carbon::now();
				$new_session->user_id = $user_id;
				$new_session->uuid = $uuid;
				$new_session->ip = request()->ip();
				$new_session->user_agent = request()->userAgent();
				$new_session->request_count = 1;
				$new_session->save();
			}
		}
	}

}
