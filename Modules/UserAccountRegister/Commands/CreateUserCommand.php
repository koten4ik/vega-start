<?php


namespace Modules\UserAccountRegister\Commands;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Modules\MlmNetwork\Services\MlmNetworkService;
use Modules\User\Models\UserModel;
use Modules\ZSupport\App\Exceptions\LogicException;
use Modules\ZSupport\App\Helpers\Str;

class CreateUserCommand
{
    public function __construct(
        private MlmNetworkService $mlmNetworkService,
    )
    {
    }

    //todo может это в сущность или в сервис?
    public function execute($data)
    {
        if (isset($data['email']) === true
            && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new LogicException('Некорректный Email');
        }


        if (isset($data['login']) === false) {
            $data['login'] = Str::translitIt($data['name']);
            $data['login'] = str_replace('-', '', $data['login']);
        }


        $user = null;


        if (isset($data['email']) === true) {
            $user = UserModel::where('email', $data['email'])->first();
        }
        if ($user === null) {
            $user = new UserModel();
        }

        $data['salt'] = md5(time());

        if (isset($data['password']) === false) {
            //$data['password'] = UserService::genPassword(8);
            $data['hash'] = null;
        } else $data['hash'] = Hash::make($data['password']);;

        //todo ! сделать на random_bytes или UUID
        // через поиск по "microtime" найти все присвоения activkey и вынести в сервис
        if (isset($data['activkey']) === false)
            $data['activkey'] = md5(microtime() . ($data['password'] ?? 'asfg3fds2342gf'));

        if (isset($data['status']) === false) {
            $data['status'] = UserModel::STATUS_NOACTIVE;
        }

        //dd($data);
        $user->login = $data['login'];
        $user->email = $data['email'] ?? null;
        $user->password = $data['hash'];

        /*$user->salt = $data['salt'];
        $user->hash = $data['hash'];
        $user->activkey = $data['activkey'];
        $user->type = UserModel::ROLE_USER;
        $user->status = $data['status'];*/

        $user->profile_gender = $data['profile_gender'] ?? null;
        $user->profile_last_name = $data['profile_last_name'] ?? null;
        $user->profile_first_name = $data['profile_first_name'] ?? null;
        $user->profile_middle_name = $data['profile_middle_name'] ?? null;
        $user->country_id = $data['country_id'] ?? null;
        $user->profile_phone = preg_replace('/\D/', '', $data['profile_phone'] ?? null);
        if (@$data['profile_birthday'] && is_array($data['profile_birthday'])) {
            $birthday = \Carbon\Carbon::createFromDate(
                $data['profile_birthday']['year'],
                $data['profile_birthday']['month'],
                $data['profile_birthday']['day']
            );
            $user->profile_birthday = $birthday;
        }

        $user->saveOrFail();

        $sponsor = UserModel::whereLogin($data['sponsor_login'])->orWhere('email', $data['sponsor_login'])->first();
        if ($sponsor) {
            /*проверка есть ли юзер в бинарной системе*/
            if (!$sponsor->binary) {
                throw new LogicException('У спонсора не заполнена структура. Регистрация сейчас не возможна.');
            }

            $user->sponsor_id = $sponsor->id;
            $user->saveOrFail();

            session()->forget('referrer_login');
        } else {
            Log::info('спонсор не найден ' . $data['sponsor_login']);
        }

        return $user;
    }
}
