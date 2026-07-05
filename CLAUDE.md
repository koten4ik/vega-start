# Инструкции по проекту

## Стек

- **Backend**: Laravel (PHP)
- **Frontend**: Чистый Blade-шаблоны. Без Inertia, без Vue, без React.
- **CSS**: Голый CSS. Файлы класть в `public/css/`. Подключать через `<link>` в blade.
- **JS**: Голый JS. Файлы класть в `public/js/`. Подключать через `<script>` в blade.
- **Без сборщиков**: Не использовать Vite, Webpack, npm-сборки, Tailwind, Alpine.js и любые CDN-библиотеки без явного разрешения.

## Архитектура — модульная система

Весь код живёт в `Modules/`. Каждый модуль — отдельная фича/домен.

### Структура модуля

```
Modules/
└── ModuleName/
    ├── Commands/          # Бизнес-команды (единица бизнес-логики)
    │   └── DoSomethingCommand.php
    ├── Http/
    │   ├── Controllers/   # Контроллеры — тонкие, только вызов команд
    │   │   └── SomeController.php
    │   ├── Requests/      # Form Request валидация
    │   │   └── SomeRequest.php
    │   ├── Routes/
    │   │   └── web.php    # Маршруты модуля
    │   └── Views/         # Blade-шаблоны модуля
    │       └── some_page.blade.php
    ├── Models/            # Eloquent-модели (если нужны для модуля)
    ├── Queries/           # Query-объекты для выборок из БД
    │   └── SomeByFieldQuery.php
    ├── Rules/             # Validation Rules
    ├── Services/          # Сервисы (вспомогательная логика)
    └── ViewModels/        # Преобразование модели в массив полей для вьюхи
        └── SomeViewModel.php
```

> Папки `Commands`, `Queries`, `Models`, `Services`, `Rules`, `ViewModels` создаются по необходимости — не все обязательны.

### Паттерны

**Контроллер** — максимально тонкий, только DI и вызов команды:
```php
class SomeController extends VegaController
{
    public function page()
    {
        return $this->render($this->getModuleName() . '::view_name');
    }

    public function action(SomeRequest $request, DoSomethingCommand $command)
    {
        if ($command->execute($request))
            return redirect(route('some.route'));
    }
}
```

**Command** — вся бизнес-логика здесь:
```php
class DoSomethingCommand
{
    public function __construct(private SomeDependency $dep) {}

    public function execute($data): bool
    {
        // логика
        return true;
    }
}
```

**Query** — статический метод, возвращает Builder:
```php
class UserByEmailQuery
{
    public static function get($email)
    {
        return UserModel::where('email', $email);
    }
}
```

**Render** — всегда через `$this->render()` из `VegaController`, не через `view()` напрямую:
```php
return $this->render('ModuleName::blade_name', $data);
```

**ViewModel** — модель никогда не передаётся во вьюху напрямую. Преобразование модели в массив полей для вьюхи делается через класс `*ViewModel` с одним статическим методом `data()`:
```php
class ProfileViewModel
{
    public static function data(UserModel $user): array
    {
        $data = [];
        if (!$user) return false;
        
        $data = [
            'name' => $user->name,
            'login' => $user->login,
        ];
        
        return $data;
    }
}
```
Обычно вызывается из Command, который отдаёт результат во вьюху:
```php
public function execute()
{
    return [
        'profile' => ProfileViewModel::data(Auth::user()),
    ];
}
```
Во вьюхе используется `$profile['name']`, а не `$user->name`.

**Blade-имя** — формат `ModuleName::file_name` (без папки Views, Laravel резолвит сам через сервис-провайдер).

### VegaController

Базовый контроллер в `Modules/ZSupport/App/Controllers/VegaController.php`.
- Все контроллеры наследуют его.
- `$this->render($view, $data)` — рендер blade с автоматической передачей `meta`, `site_data`, `system`, csrf.
- `$this->sendResponse($data)` — JSON-ответ.
- `$this->getModuleName()` — возвращает имя модуля из namespace контроллера.

## Git

После создания или изменения файлов — делать `git add <файл>`. Коммит не делать, пользователь коммитит сам.

## Запрещено без явного разрешения

- **`Modules/ZSupport/App/`** — это движок проекта. Не трогать, не изменять файлы внутри.
- Не использовать `view()` напрямую в контроллерах — только `$this->render()`.
- Не писать бизнес-логику в контроллерах — выносить в Commands.
- Не передавать Eloquent-модель напрямую во вьюху — преобразовывать через `*ViewModel::data()`.
- Не создавать файлы вне `Modules/` (кроме `public/css/`, `public/js/`, `routes/web.php` при крайней необходимости).

## Именование

| Сущность | Пример |
|---|---|
| Модуль | `UserAccountAuth`, `ZSystem` |
| Контроллер | `AuthController`, `RegisterController` |
| Command | `LoginUserCommand`, `CreateUserCommand` |
| Query | `UserByEmailQuery`, `UserByLoginQuery` |
| Request | `LoginRequest`, `RegisterRequest` |
| Model | `UserModel`, `PasswordResetTokenModel` |
| Service | `UserService`, `OAuthGoogleService` |
| Rule | `EmailRule`, `UserPasswordRule` |
| ViewModel | `ProfileViewModel`, `OrderViewModel` (метод всегда `data()`) |
| Blade | `login.blade.php`, `change_password.blade.php` (snake_case) |
| Route name | `user.auth.login`, `user.cabinet` (dot-notation, с префиксом модуля) |
