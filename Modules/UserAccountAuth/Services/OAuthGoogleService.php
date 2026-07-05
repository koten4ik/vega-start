<?php


namespace Modules\UserAccountAuth\Services;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\ZSupport\App\Helpers\Env;
use Modules\User\Services\UserService;
use Modules\User\Models\UserModel;
use Modules\UserAccountAuth\Models\UserOauth;
use Modules\UserAccountRegister\Commands\CreateUserCommand;

class OAuthGoogleService
{
	const NET_ALIAS = 'google';

	public static function getUrl()
	{
		return 'https://accounts.google.com/o/oauth2/auth?' . urldecode(http_build_query(self::getParams(true)));
	}

	public static function getParams($to_url = false)
	{
		$arr = array(
			'client_id' => config('services.google.client_id'),
			'redirect_uri' => route('user.auth.google'),
			'client_secret' => config('services.google.client_secret'),
			'display' => 'popup',
			'response_type' => 'code',
			'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
		);

		if ($to_url) {
			unset($arr['client_secret']);
		}
		return $arr;
	}

	public static function getUserDataByToken($token)
	{
		$client = new \GuzzleHttp\Client();
		$url_info = 'https://www.googleapis.com/oauth2/v1/userinfo';
		$response = $client->get($url_info, ['headers' => ['Authorization' => 'Bearer ' . $token]]);
		$userInfo = json_decode($response->getBody()->getContents(), true);
		$data = [
			'oauth_id' => 'gl' . $userInfo['id'],
			'email' => $userInfo['email'] ?? '',
			'first_name' => $userInfo['given_name'] ?? '',
			'last_name' => $userInfo['family_name'] ?? '',
			//'photo'=>$userInfo['picture'],
			'network' => self::NET_ALIAS,
		];

		$data['name'] = $data['first_name'] . ' ' . $data['last_name'];
		$data['oauth_login'] = $data['email'];

		return $data;
	}

	public static function getTokenByCode($code)
	{
		$params = self::getParams();
		$params['code'] = $code;
		$params['grant_type'] = 'authorization_code';

		$url_token = 'https://accounts.google.com/o/oauth2/token';
		$client = new \GuzzleHttp\Client();
		$token = false;

		$response = $client->request('POST', $url_token, ['form_params' => $params]);
		$tokenInfo = json_decode($response->getBody()->getContents(), true);

		if (count($tokenInfo) > 0 && isset($tokenInfo['access_token'])) {
			$token = $tokenInfo['access_token'];
		}
		//dd($token);

		return $token;
	}
}
