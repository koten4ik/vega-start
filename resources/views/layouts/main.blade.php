<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(!app()->environment('production'))
        <meta name="robots" content="noindex, nofollow">
    @endif

    {!! \Modules\ZSupport\App\Services\MetaTags::draw() !!}

    <link rel="shortcut icon" type="image/png" href="/favicon-logo.png"/>
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/base.css')}}"/>

    @stack('styles')
</head>

<body>

@php($cartItemsCount = \Modules\ShopCart\Services\CartService::itemsCount())

<header class="site-header">
    <div class="site-header__inner">
        <a href="{{ route('site.main.page') }}" class="site-header__logo">Vega Start</a>

        <nav class="site-header__nav">
            <a href="{{ route('shop.catalog.page') }}">Каталог</a>
            <a href="{{ route('shop.cart.page') }}">
                Корзина
                @if($cartItemsCount > 0)
                    <span class="site-header__badge">{{ $cartItemsCount }}</span>
                @endif
            </a>
        </nav>

        <div class="site-header__account">
            @auth
                <a href="{{ route('shop.order.list') }}">Мои заказы</a>
                <a href="{{ route('user.auth.logout') }}">Выход</a>
            @else
                <a href="{{ route('user.auth.loginPage') }}">Войти</a>
                <a href="{{ route('user.register') }}">Регистрация</a>
            @endauth
        </div>
    </div>
</header>

<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__copy">&copy; {{ date('Y') }} Vega Start. Все права защищены.</div>
        <nav class="site-footer__nav">
            <a href="{{ route('shop.catalog.page') }}">Каталог</a>
            <a href="{{ route('shop.cart.page') }}">Корзина</a>
        </nav>
    </div>
</footer>

<script src="{{ \Modules\ZSupport\App\Helpers\H::mtimeFix('/js/base.js') }}" defer></script>


@stack('scripts')

</body>
</html>
