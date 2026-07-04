<?php


namespace Modules\UserAccountAuth\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Modules\UserAccountAuth\Commands\OAuth2GetTokenCommand;
use Modules\ZSupport\App\Helpers\Env;
use Modules\User\Services\UserService;
use Modules\User\Models\UserModel;
use Modules\UserAccountAuth\Models\UserOauth;
use Modules\UserAccountRegister\Commands\CreateUserCommand;

class OAuthService
{
	public static function getUserByData($oauth_data, CreateUserCommand $createUserCommand)
	{
		$user = null;

		//ищем привязку
		$user_oauth = UserOauth::where('oauth_id', $oauth_data['oauth_id'])->first();
		if ($user_oauth != null && isset($user_oauth->user)) {
			$user = $user_oauth->user;
		}
		//ищем по почте если указана
		if ($user == null && isset($oauth_data['email']) === true && $oauth_data['email'] !='') {
			$user = UserModel::where('email', $oauth_data['email'])->first();
		}
		//создаем если не нашли
		if ($user == null) {
			$oauth_data['status'] = UserModel::STATUS_ACTIVE;
			if(isset($oauth_data['email']) && $oauth_data['email']===''){
				unset($oauth_data['email']);
			}
			$user = $createUserCommand->execute($oauth_data);
			//todo слать письмо регистрации???
		}

		//для входа через телегу
		if (isset($oauth_data['activkey'])) {
			$user->activkey = $oauth_data['activkey'];
			$user->save();
		}

		return $user;
	}

	public static function attach($data, $user_id)
	{
		//$user_oauth = UserOauth::where('oauth_id', $data['id'])->first();
		UserOauth::where('oauth_id', strval($data['oauth_id']))->delete();
		$user_oauth = UserOauth::create([
			'user_id' => $user_id,
			'oauth_id' => $data['oauth_id'],
			'network' => $data['network'],
			'name' => $data['name'],
			'oauth_login' => $data['oauth_login'],
		]);
	}

	public static function dettach($network_id, $user_id)
	{
		UserOauth::where('network', $network_id)->where('user_id', $user_id)->delete();
	}

	public static function networkList($user_id)
	{
		$list = [
			OAuthGoogleService::NET_ALIAS => ['exist' => false, 'login' => null],
			OAuthVKService::NET_ALIAS => ['exist' => false, 'login' => null],
			OAuthTgService::NET_ALIAS => ['exist' => false, 'login' => null],
		];
		$list_raw = UserOauth::where('user_id', $user_id)->get();
		if($list_raw){
			foreach ($list_raw as $oauth){
				$list[$oauth->network] = [
					'exist' => true,
					'login' => $oauth->oauth_login
				];
			}
		}

		return $list;
	}
}
