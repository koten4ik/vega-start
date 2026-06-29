
в composer.json -
"autoload": {
    "psr-4": {
        ...
        "Modules\\": "Modules/"
    }
},
composer dump-autoload

Modules\ZSupport\App\Boot::execute(); - в app/Providers/AppServiceProvider.php в function boot()

настройка исключений в Modules/ZSupport/App/Exceptions/Handler.php
регистрация запросов в Modules/ZSupport/App/Middleware/RegisterRequestMiddleware.php
