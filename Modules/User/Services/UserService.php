<?php


namespace Modules\User\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\User\Enums\UserGender;
use Modules\User\Models\UserModel;

class UserService
{


    public static function getAvatar($user)
    {
        if ($user === null) return '';

        if ($user->avatar == false) {
            if ($user->profile_gender == UserGender::male) {
                $path = '/assets/images/profile/default-male.png';
            } else $path = '/assets/images/profile/default-female.png';
        } else {
            $path = Storage::url($user->avatar);
        }

        return $path;
    }

    public static function genPassword($length = 6)
    {
        $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $size = strlen($chars) - 1;
        $password = '';
        while ($length--) {
            $password .= $chars[random_int(0, $size)];
        }
        return $password;
    }

    public static function login($user)
    {
        if($user->supplier_id == null) {
            Auth::loginUsingId($user->id, true);
            \request()->session()->regenerate();
        }

        return true;
    }

    public static function uuid(&$is_new = null)
    {
        $cook_name = 'uuid';

        if (!Cookie::has($cook_name) || !self::isValidUuid(Cookie::get($cook_name))) {
            if ($is_new !== null) $is_new = 1;
            $uuid = Str::uuid()->toString();
            Cookie::queue($cook_name, $uuid, 525600); // 525600 минут = 1 год
        } else {
            if ($is_new !== null) $is_new = 0;
            $uuid = Cookie::get($cook_name);
        }

        $uuid = mb_substr($uuid, 0, 40);

        return $uuid;
    }

    public static function isValidUuid($value)
    {
        return preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $value);
    }

    const LOGIN_AS_LINK_TTL = 120; // секунд
    public static function loginAsSecret()
    {
        return (string) ($_ENV['APP_KEY'] ?? $_SERVER['APP_KEY'] ?? getenv('APP_KEY') ?: '');
    }
    public static function loginAsSignature($userId, $expires)
    {
        return hash_hmac('sha256', $userId . '|' . $expires, self::loginAsSecret());
    }
    public static function getLoginAsUrl($user, $ttlSeconds = self::LOGIN_AS_LINK_TTL)
    {
        $expires = time() + $ttlSeconds;

        return route('user.auth.loginas', [
            'id' => $user->id,
            'expires' => $expires,
            'signature' => self::loginAsSignature($user->id, $expires),
        ]);
    }
}
