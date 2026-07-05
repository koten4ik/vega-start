@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/auth.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid auth-page">
        <div class="auth-card">
            <h1>Авторизация</h1>

            <form action="{{ route('user.auth.login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Логин или email: <span class="form-required">*</span></label>
                    <input type="text" name="login" value="{{ old('login') }}" required
                           class="form-control {{ $errors->has('login') ? 'input-error' : '' }}">
                    @error('login') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Пароль: <span class="form-required">*</span></label>
                    <input type="password" name="password" required
                           class="form-control {{ $errors->has('password') ? 'input-error' : '' }}">
                    @error('password') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Войти</button>
            </form>

            <div class="auth-card__links">
                <a href="{{ route('user.register') }}">Регистрация</a>
                <a href="{{ route('user.password.recoveryPage') }}">Забыли пароль?</a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
