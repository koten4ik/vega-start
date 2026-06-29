<?php


namespace Modules\ZSupport\App;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Laravel\Passport\Bridge\UserRepository;
use Laravel\Passport\Passport;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use League\OAuth2\Server\AuthorizationServer;

class Boot
{
	public static function execute()
	{
		date_default_timezone_set('Europe/Moscow');
		DB::enableQueryLog();

        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }

        //Автоматическая регистрация ВЬЮХ и РОУТОВ
        $modulesPath = base_path('Modules');
        if (File::exists($modulesPath)) {
            $modules = File::directories($modulesPath);
            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $viewPath = $modulePath . '/Http/Views';
                if (File::isDirectory($viewPath)) {
                    View::addNamespace($moduleName, $viewPath);
                }
                $routePath = $modulePath . '/Http/Routes/web.php';
                if (File::exists($routePath)) {
                    Route::middleware('web')
                        // Опционально: можно добавить префикс по имени модуля
                        // ->prefix(strtolower($moduleName))
                        ->group($routePath);
                }
            }
        }
	}
}
