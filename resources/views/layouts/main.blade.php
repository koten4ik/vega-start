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


<main class="flex-grow-1">
    @yield('content')
</main>

<script src="{{ \Modules\ZSupport\App\Helpers\H::mtimeFix('/js/base.js') }}" defer></script>


@stack('scripts')

</body>
</html>
