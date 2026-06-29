<?php

namespace Modules\ZSupport\App\Controllers;

use App\Exceptions\JsonBusinessException;
use App\Exceptions\LogicException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\User\Services\UserRoleService;
use Modules\ZSupport\App\Helpers\Dbg;
use Modules\ZSupport\App\Helpers\H;
use Modules\ZSupport\App\Services\Logger\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;
use Modules\Site\ViewModels\SiteDataViewModel;
use Modules\ZSupport\App\Services\MetaTags;
use Modules\ZSupport\App\Services\SessionService;
use Modules\ZSupport\Domain\OnRenderProcesses;

class VegaController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function render($view_name, $data = [])
    {
    	$user_is_admin = UserRoleService::isAdmin(Auth::user());

		if(request()->wantsJson()){
			return $this->sendResponse($data);
		}

		if(isset($data['meta']) == false){
			$data['meta'] = MetaTags::mainMeta();
		}

		$data['site_data'] = [];

		$data['system']['user_agent'] = request()->header('User-Agent');
		$data['system']['request'] = request()->all();
		$data['system']['component_name'] = $view_name;
		$data['system']['csrf'] = csrf_token();
		// 'alerts' переехал в SiteDataViewModel

        //вызов доменной логики
        $arr = OnRenderProcesses::call();
        $data['site_data'] = array_merge($data['site_data'], $arr);


		if($user_is_admin == true) {
			$data['system'] = array_merge($data['system'], Dbg::stats());
		}
        if (request()->has('dd') && $user_is_admin){
        	dd($data);
        }
		if (request()->has('dds') && $user_is_admin){
			echo Dbg::getSqlQueriesString();
			die();
		}

        return view($view_name,$data);
    }

    public function sendResponse($result = [], $message = 'success')
    {
        if (!is_array($result)) {
            $result = ['success' => $result];
        }

		$user_is_admin = UserRoleService::isAdmin(Auth::user());

		if($user_is_admin == true) {
			$result['system'] = Dbg::stats();
		}

        $response = $result;
		/*[
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];*/

		$status_code = 200;
		if(isset($response['status_code'])){
			$status_code = $response['status_code'];
			unset($response['status_code']);
		}

        return response()->json($response, $status_code, [], JSON_UNESCAPED_UNICODE);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
		throw new LogicException($error);

    	/*Log::error($error);
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);*/
    }

    protected function getModuleName(): string
    {
        // Получаем полный путь к классу контроллера
        $class = static::class;
        // Результат: Modules\SiteMainPage\Http\Controllers\MainPageController

        // Разбиваем строку по бакслешу и берем второй элемент (имя модуля)
        return explode('\\', $class)[1];
    }
}
