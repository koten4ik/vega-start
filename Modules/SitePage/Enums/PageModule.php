<?php

namespace Modules\SitePage\Enums;

enum PageModule: string
{
    case INDEX = 'index';
    case TEXT_PAGE = 'text_page';
    case CATALOG = 'catalog';
    case LOGIN = 'login';
    case REGISTRATION = 'registration';
    case PASSWORD_RESET = 'password_reset';
    case CART = 'cart';
    case PROFILE = 'profile';

    public function label(): string
    {
        return match ($this) {
            self::INDEX => 'Главная',
            self::TEXT_PAGE => 'Текстовая страница',
            self::CATALOG => 'Каталог',
            self::LOGIN => 'Логин',
            self::REGISTRATION => 'Регистрация',
            self::PASSWORD_RESET => 'Восстановление пароля',
            self::CART => 'Корзина',
            self::PROFILE => 'Личный кабинет',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
